<?php

declare(strict_types=1);

namespace App\Modules\Maintenance;

use App\Modules\AbstractModule;

final class MaintenanceModule extends AbstractModule
{
    public function key(): string
    {
        return 'maintenance';
    }
}
