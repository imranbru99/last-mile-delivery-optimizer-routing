<?php

declare(strict_types=1);

namespace App\Modules\DriverPwa;

use App\Modules\AbstractModule;

final class DriverPwaModule extends AbstractModule
{
    public function key(): string
    {
        return 'driver_pwa';
    }
}
