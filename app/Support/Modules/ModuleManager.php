<?php

declare(strict_types=1);

namespace App\Support\Modules;

use App\Models\Business;
use App\Models\BusinessModule;
use App\Models\Module;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

final class ModuleManager
{
    public function registry(): Collection
    {
        return collect(config('modules.registry', []))->keyBy('key');
    }

    public function categories(): array
    {
        return config('modules.categories', []);
    }

    public function presets(): array
    {
        return config('modules.presets', []);
    }

    public function find(string $key): ?array
    {
        /** @var array|null $module */
        $module = $this->registry()->get($key);

        return $module;
    }

    public function isAlwaysOn(string $key): bool
    {
        return (bool) ($this->find($key)['always_on'] ?? false);
    }

    public function isEnabled(string $module, ?Business $business = null): bool
    {
        if ($this->isAlwaysOn($module)) {
            return true;
        }

        $business ??= app(TenantContext::class)->business();

        if ($business === null) {
            return (bool) ($this->find($module)['default'] ?? false);
        }

        return (bool) ($this->enabledMap($business)[$module] ?? false);
    }

    /**
     * @return array<string, bool>
     */
    public function enabledMap(Business $business): array
    {
        $ttl = (int) config('modules.cache_ttl', 300);
        $key = config('modules.cache_key', 'lmdo.modules').".{$business->id}";

        return Cache::remember($key, $ttl, function () use ($business): array {
            $defaults = $this->registry()->mapWithKeys(
                fn (array $m): array => [
                    $m['key'] => (bool) ($m['default'] ?? false) || (bool) ($m['always_on'] ?? false),
                ]
            )->all();

            $overrides = BusinessModule::query()
                ->where('business_id', $business->id)
                ->get(['module_key', 'is_enabled'])
                ->mapWithKeys(fn (BusinessModule $row): array => [$row->module_key => (bool) $row->is_enabled])
                ->all();

            foreach ($this->registry() as $mod) {
                if ($mod['always_on'] ?? false) {
                    $overrides[$mod['key']] = true;
                }
            }

            return array_merge($defaults, $overrides);
        });
    }

    public function forgetCache(?Business $business = null): void
    {
        if ($business) {
            Cache::forget(config('modules.cache_key', 'lmdo.modules').".{$business->id}");

            return;
        }

        Business::query()->pluck('id')->each(
            fn ($id) => Cache::forget(config('modules.cache_key', 'lmdo.modules').".{$id}")
        );
    }

    /**
     * @return list<string>
     */
    public function unmetDependencies(string $module, Business $business): array
    {
        $deps = $this->find($module)['dependencies'] ?? [];
        $enabled = $this->enabledMap($business);

        return array_values(array_filter(
            $deps,
            fn (string $dep): bool => ! ($enabled[$dep] ?? false)
        ));
    }

    /**
     * @return list<string>
     */
    public function dependents(string $module, Business $business): array
    {
        $enabled = $this->enabledMap($business);

        return $this->registry()
            ->filter(function (array $m) use ($module, $enabled): bool {
                return ($enabled[$m['key']] ?? false)
                    && in_array($module, $m['dependencies'] ?? [], true);
            })
            ->keys()
            ->values()
            ->all();
    }

    public function enable(string $module, Business $business, array $settings = []): BusinessModule
    {
        if ($this->find($module) === null) {
            throw new InvalidArgumentException("Unknown module [{$module}].");
        }

        $unmet = $this->unmetDependencies($module, $business);
        if ($unmet !== []) {
            throw new RuntimeException(
                "Cannot enable [{$module}]; unmet dependencies: ".implode(', ', $unmet)
            );
        }

        return DB::transaction(function () use ($module, $business, $settings): BusinessModule {
            $row = BusinessModule::query()->updateOrCreate(
                ['business_id' => $business->id, 'module_key' => $module],
                [
                    'is_enabled' => true,
                    'settings' => $settings !== [] ? $settings : null,
                    'enabled_at' => now(),
                    'disabled_at' => null,
                ]
            );

            $this->forgetCache($business);

            return $row;
        });
    }

    public function disable(string $module, Business $business): BusinessModule
    {
        if ($this->isAlwaysOn($module)) {
            throw new RuntimeException("Module [{$module}] is always on and cannot be disabled.");
        }

        $deps = $this->dependents($module, $business);
        if ($deps !== []) {
            throw new RuntimeException(
                "Cannot disable [{$module}]; required by: ".implode(', ', $deps)
            );
        }

        return DB::transaction(function () use ($module, $business): BusinessModule {
            $row = BusinessModule::query()->updateOrCreate(
                ['business_id' => $business->id, 'module_key' => $module],
                [
                    'is_enabled' => false,
                    'disabled_at' => now(),
                ]
            );

            $this->forgetCache($business);

            return $row;
        });
    }

    public function applyPreset(string $presetKey, Business $business): void
    {
        $preset = $this->presets()[$presetKey] ?? null;
        if ($preset === null) {
            throw new InvalidArgumentException("Unknown preset [{$presetKey}].");
        }

        DB::transaction(function () use ($preset, $business, $presetKey): void {
            $business->update(['business_type' => $presetKey]);

            $wanted = array_values(array_unique(array_merge(
                $this->registry()
                    ->filter(fn (array $m): bool => ($m['always_on'] ?? false))
                    ->keys()
                    ->all(),
                $preset['modules']
            )));

            foreach ($this->registry()->keys()->reverse() as $key) {
                if (! in_array($key, $wanted, true) && ! $this->isAlwaysOn($key)) {
                    try {
                        $this->disable($key, $business);
                    } catch (RuntimeException) {
                        // Retry after dependents are handled.
                    }
                }
            }

            $remaining = $wanted;
            $guard = 0;
            while ($remaining !== [] && $guard++ < 80) {
                $progress = false;
                foreach ($remaining as $i => $key) {
                    if ($this->unmetDependencies($key, $business) === []) {
                        $this->enable($key, $business);
                        unset($remaining[$i]);
                        $progress = true;
                    }
                }
                $remaining = array_values($remaining);
                if (! $progress) {
                    break;
                }
            }

            // Second disable pass once dependents are off
            foreach ($this->registry()->keys()->reverse() as $key) {
                if (! in_array($key, $wanted, true) && ! $this->isAlwaysOn($key) && $this->isEnabled($key, $business)) {
                    try {
                        $this->disable($key, $business);
                    } catch (RuntimeException) {
                        // Keep if still required.
                    }
                }
            }

            $this->forgetCache($business);
        });
    }

    public function syncCatalogToDatabase(): int
    {
        $count = 0;
        foreach ($this->registry() as $mod) {
            Module::query()->updateOrCreate(
                ['key' => $mod['key']],
                [
                    'name' => $mod['name'],
                    'description' => $mod['description'] ?? null,
                    'icon' => $mod['icon'] ?? null,
                    'category' => $mod['category'],
                    'dependencies' => $mod['dependencies'] ?? [],
                    'version' => $mod['version'] ?? '1.0.0',
                    'default_enabled' => (bool) ($mod['default'] ?? false),
                    'always_on' => (bool) ($mod['always_on'] ?? false),
                    'pricing_tier' => $mod['tier'] ?? 'starter',
                    'settings_schema' => $mod['settings_schema'] ?? [],
                    'what_you_lose' => $mod['what_you_lose'] ?? null,
                    'is_active' => true,
                ]
            );
            $count++;
        }

        return $count;
    }
}
