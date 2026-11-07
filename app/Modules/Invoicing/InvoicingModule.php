<?php

declare(strict_types=1);

namespace App\Modules\Invoicing;

use App\Modules\AbstractModule;

final class InvoicingModule extends AbstractModule
{
    public function key(): string
    {
        return 'invoicing';
    }
}
