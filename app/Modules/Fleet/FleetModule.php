<?php

declare(strict_types=1);

namespace App\Modules\Fleet;

use App\Modules\AbstractModule;

final class FleetModule extends AbstractModule
{
    public function key(): string
    {
        return 'fleet';
    }
}
