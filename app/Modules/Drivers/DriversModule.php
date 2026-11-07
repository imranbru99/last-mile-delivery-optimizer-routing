<?php

declare(strict_types=1);

namespace App\Modules\Drivers;

use App\Modules\AbstractModule;

final class DriversModule extends AbstractModule
{
    public function key(): string
    {
        return 'drivers';
    }
}
