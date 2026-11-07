<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Finance\PricingEngine;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\Performance\DrutogotiPageCache;
use App\Services\Routing\RoutingManager;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class);
        $this->app->singleton(ModuleManager::class);
        $this->app->singleton(RoutingManager::class);
        $this->app->singleton(PricingEngine::class);
        $this->app->singleton(PaymentGatewayManager::class);
        $this->app->singleton(DrutogotiPageCache::class);
    }

    public function boot(): void
    {
        Blade::if('module', function (string $key): bool {
            return app(ModuleManager::class)->isEnabled($key);
        });

        Blade::directive('endmodule', function (): string {
            return '<?php endif; ?>';
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): string => view('filament.components.demo-logins')->render(),
        );
    }
}
