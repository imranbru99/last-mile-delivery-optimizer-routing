<?php

declare(strict_types=1);

namespace App\Modules\DataExport;

use App\Modules\AbstractModule;

final class DataExportModule extends AbstractModule
{
    public function key(): string
    {
        return 'data_export';
    }
}
