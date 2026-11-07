<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

trait SuperAdminOnly
{
    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
