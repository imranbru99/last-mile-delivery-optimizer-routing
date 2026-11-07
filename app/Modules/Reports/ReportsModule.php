<?php

declare(strict_types=1);

namespace App\Modules\Reports;

use App\Modules\AbstractModule;

final class ReportsModule extends AbstractModule
{
    public function key(): string
    {
        return 'reports';
    }
}
