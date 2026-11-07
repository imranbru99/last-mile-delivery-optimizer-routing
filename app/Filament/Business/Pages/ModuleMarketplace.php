<?php

declare(strict_types=1);

namespace App\Filament\Business\Pages;

use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use RuntimeException;

class ModuleMarketplace extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Module Marketplace';

    protected static ?string $title = 'Module Marketplace';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.business.pages.module-marketplace';

    public string $categoryFilter = 'all';

    public string $search = '';

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessPanel(filament()->getCurrentPanel()) ?? false;
    }

    public function getModulesProperty(): Collection
    {
        $manager = app(ModuleManager::class);
        $business = app(TenantContext::class)->business() ?? auth()->user()?->business;

        if (! $business) {
            return collect();
        }

        $enabled = $manager->enabledMap($business);
        $categories = $manager->categories();

        return $manager->registry()
            ->when($this->categoryFilter !== 'all', fn (Collection $c) => $c->where('category', $this->categoryFilter))
            ->when($this->search !== '', function (Collection $c) {
                $q = strtolower($this->search);

                return $c->filter(fn (array $m) => str_contains(strtolower($m['name'].' '.$m['description']), $q));
            })
            ->map(function (array $module) use ($enabled, $manager, $business, $categories): array {
                $key = $module['key'];

                return [
                    ...$module,
                    'enabled' => (bool) ($enabled[$key] ?? false),
                    'category_label' => $categories[$module['category']]['label'] ?? $module['category'],
                    'unmet' => $manager->unmetDependencies($key, $business),
                    'dependents' => $manager->dependents($key, $business),
                    'tier_label' => config('modules.pricing_tiers.'.$module['tier'].'.label', $module['tier']),
                    'monthly' => config('modules.pricing_tiers.'.$module['tier'].'.monthly', 0),
                ];
            })
            ->values();
    }

    public function getCategoriesProperty(): array
    {
        return ['all' => ['label' => 'All modules', 'letter' => '*']] + app(ModuleManager::class)->categories();
    }

    public function toggle(string $key): void
    {
        $manager = app(ModuleManager::class);
        $business = app(TenantContext::class)->business() ?? auth()->user()?->business;

        if (! $business) {
            Notification::make()->title('No business context')->danger()->send();

            return;
        }

        try {
            if ($manager->isEnabled($key, $business)) {
                $manager->disable($key, $business);
                Notification::make()->title("Disabled {$key}")->success()->send();
            } else {
                $manager->enable($key, $business);
                Notification::make()->title("Enabled {$key}")->success()->send();
            }
        } catch (RuntimeException $e) {
            Notification::make()->title('Action blocked')->body($e->getMessage())->danger()->send();
        }
    }
}
