<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Support\Modules\ModuleManager;

trait ModuleAware
{
    public static function moduleKey(): ?string
    {
        return property_exists(static::class, 'module')
            ? static::$module
            : null;
    }

    public static function moduleIsEnabled(): bool
    {
        $key = static::moduleKey();

        if ($key === null) {
            return true;
        }

        return app(ModuleManager::class)->isEnabled($key);
    }

    public static function canAccess(): bool
    {
        return static::moduleIsEnabled();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::moduleIsEnabled();
    }
}
