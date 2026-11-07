<?php

declare(strict_types=1);

namespace App\Modules\CustomerTracking;

use App\Modules\AbstractModule;

final class CustomerTrackingModule extends AbstractModule
{
    public function key(): string
    {
        return 'customer_tracking';
    }
}
