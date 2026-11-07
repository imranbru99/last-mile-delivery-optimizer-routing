<?php

declare(strict_types=1);

namespace App\Modules\Orders;

use App\Modules\AbstractModule;

final class OrdersModule extends AbstractModule
{
    public function key(): string
    {
        return 'orders';
    }
}
