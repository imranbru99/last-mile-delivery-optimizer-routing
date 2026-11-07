<?php

declare(strict_types=1);

namespace App\Modules\Territories;

use App\Modules\AbstractModule;

final class TerritoriesModule extends AbstractModule
{
    public function key(): string
    {
        return 'territories';
    }
}
