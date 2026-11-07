<?php

declare(strict_types=1);

namespace App\Modules\NlReports;

use App\Modules\AbstractModule;

final class NlReportsModule extends AbstractModule
{
    public function key(): string
    {
        return 'nl_reports';
    }
}
