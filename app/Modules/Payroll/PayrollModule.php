<?php

declare(strict_types=1);

namespace App\Modules\Payroll;

use App\Modules\AbstractModule;

final class PayrollModule extends AbstractModule
{
    public function key(): string
    {
        return 'payroll';
    }
}
