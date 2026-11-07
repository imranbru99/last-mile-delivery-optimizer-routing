<?php

declare(strict_types=1);

namespace App\Modules\Compliance;

use App\Modules\AbstractModule;

final class ComplianceModule extends AbstractModule
{
    public function key(): string
    {
        return 'compliance';
    }
}
