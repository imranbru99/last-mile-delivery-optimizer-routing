<?php

declare(strict_types=1);

namespace App\Modules\RouteOptimization;

use App\Modules\AbstractModule;
use App\Modules\RouteOptimization\Services\RouteOptimizationService;

final class RouteOptimizationModule extends AbstractModule
{
    public function key(): string
    {
        return 'route_optimization';
    }

    public function register(): void
    {
        $this->app()->singleton(RouteOptimizationService::class);
    }

    private function app(): \Illuminate\Contracts\Foundation\Application
    {
        return app();
    }
}
