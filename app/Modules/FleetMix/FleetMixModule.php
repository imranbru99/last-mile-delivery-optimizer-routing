<?php

declare(strict_types=1);

namespace App\Modules\FleetMix;

use App\Modules\AbstractModule;

final class FleetMixModule extends AbstractModule
{
    public function key(): string
    {
        return 'fleet_mix';
    }
}
