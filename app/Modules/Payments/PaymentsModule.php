<?php

declare(strict_types=1);

namespace App\Modules\Payments;

use App\Modules\AbstractModule;

final class PaymentsModule extends AbstractModule
{
    public function key(): string
    {
        return 'payments';
    }
}
