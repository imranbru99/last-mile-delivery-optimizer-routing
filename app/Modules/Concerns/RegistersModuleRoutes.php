<?php

declare(strict_types=1);

namespace App\Modules\Concerns;

use Illuminate\Support\Facades\Route;

trait RegistersModuleRoutes
{
    protected function loadModuleRoutes(string $moduleKey, string $routesPath, string $prefix = ''): void
    {
        if (! is_file($routesPath)) {
            return;
        }

        Route::middleware(['web', 'auth', 'tenant', "module:{$moduleKey}"])
            ->prefix($prefix !== '' ? $prefix : "m/{$moduleKey}")
            ->name("modules.{$moduleKey}.")
            ->group($routesPath);
    }

    protected function loadModuleApiRoutes(string $moduleKey, string $routesPath): void
    {
        if (! is_file($routesPath)) {
            return;
        }

        Route::middleware(['api', 'auth:sanctum', 'tenant', "module:{$moduleKey}"])
            ->prefix("api/v1/{$moduleKey}")
            ->name("api.v1.{$moduleKey}.")
            ->group($routesPath);
    }
}
