<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Pages;

use App\Filament\Concerns\SuperAdminOnly;
use Drutogoti\Models\DrutogotiRequest;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class SystemHealth extends Page
{
    use SuperAdminOnly;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $navigationLabel = 'System health';

    protected static ?string $title = 'System health';

    protected static ?int $navigationSort = 50;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected string $view = 'filament.super-admin.pages.system-health';

    /** @var array<string, array{label: string, status: string, detail: string}> */
    public array $checks = [];

    public function mount(): void
    {
        $this->checks = $this->runChecks();
    }

    public function refreshChecks(): void
    {
        $this->checks = $this->runChecks();
    }

    /**
     * @return array<string, array{label: string, status: string, detail: string}>
     */
    private function runChecks(): array
    {
        return [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'redis' => $this->checkRedis(),
            'drutogoti' => $this->checkDrutogoti(),
        ];
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'label' => 'Database',
                'status' => 'ok',
                'detail' => 'Connection `'.config('database.default').'` is reachable.',
            ];
        } catch (Throwable $exception) {
            return [
                'label' => 'Database',
                'status' => 'error',
                'detail' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function checkCache(): array
    {
        $store = config('cache.default');
        $probeKey = 'lmdo.super_admin.health.'.Str::uuid();

        try {
            Cache::put($probeKey, 'ok', 10);
            $value = Cache::get($probeKey);
            Cache::forget($probeKey);

            if ($value !== 'ok') {
                return [
                    'label' => 'Cache',
                    'status' => 'warning',
                    'detail' => "Store `{$store}` did not round-trip the probe value.",
                ];
            }

            return [
                'label' => 'Cache',
                'status' => 'ok',
                'detail' => "Store `{$store}` read/write succeeded.",
            ];
        } catch (Throwable $exception) {
            return [
                'label' => 'Cache',
                'status' => 'error',
                'detail' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function checkQueue(): array
    {
        $connection = config('queue.default');

        return [
            'label' => 'Queue',
            'status' => 'ok',
            'detail' => "Default connection: `{$connection}`.",
        ];
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function checkRedis(): array
    {
        $usesRedis = in_array(config('cache.default'), ['redis'], true)
            || in_array(config('queue.default'), ['redis'], true)
            || config('database.redis.client');

        if (! class_exists(Redis::class)) {
            return [
                'label' => 'Redis',
                'status' => 'warning',
                'detail' => 'Redis extension / facade not available.',
            ];
        }

        try {
            $pong = Redis::connection()->ping();
            $response = is_string($pong) ? $pong : 'PONG';

            return [
                'label' => 'Redis',
                'status' => 'ok',
                'detail' => 'Ping: '.$response.($usesRedis ? ' (used by cache/queue).' : ''),
            ];
        } catch (Throwable $exception) {
            return [
                'label' => 'Redis',
                'status' => 'error',
                'detail' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function checkDrutogoti(): array
    {
        if (! class_exists(DrutogotiRequest::class)) {
            return [
                'label' => 'Drutogoti',
                'status' => 'warning',
                'detail' => 'Package `imrandevbd/drutogoti` is not installed.',
            ];
        }

        $enabled = (bool) config('drutogoti.enabled', true);
        $tableExists = Schema::hasTable('drutogoti_requests');

        if (! $enabled) {
            return [
                'label' => 'Drutogoti',
                'status' => 'warning',
                'detail' => 'Observability is disabled via config.',
            ];
        }

        if (! $tableExists) {
            return [
                'label' => 'Drutogoti',
                'status' => 'error',
                'detail' => 'Enabled but `drutogoti_requests` table is missing — run migrations.',
            ];
        }

        try {
            $recent = DrutogotiRequest::query()->latest('id')->value('created_at');

            return [
                'label' => 'Drutogoti',
                'status' => 'ok',
                'detail' => $recent
                    ? 'Capturing requests; latest at '.$recent->toDateTimeString().'.'
                    : 'Ready — no captured requests yet.',
            ];
        } catch (Throwable $exception) {
            return [
                'label' => 'Drutogoti',
                'status' => 'error',
                'detail' => $exception->getMessage(),
            ];
        }
    }
}
