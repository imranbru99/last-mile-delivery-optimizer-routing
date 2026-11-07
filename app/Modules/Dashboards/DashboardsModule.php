<?php

declare(strict_types=1);

namespace App\Modules\Dashboards;

use App\Modules\AbstractModule;

final class DashboardsModule extends AbstractModule
{
    public function key(): string
    {
        return 'dashboards';
    }
}
