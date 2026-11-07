<?php

declare(strict_types=1);

namespace App\Modules\Fuel;

use App\Modules\AbstractModule;

final class FuelModule extends AbstractModule
{
    public function key(): string
    {
        return 'fuel';
    }
}
