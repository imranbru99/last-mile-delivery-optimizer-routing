<?php

declare(strict_types=1);

namespace App\Modules\CrossDock;

use App\Modules\AbstractModule;

final class CrossDockModule extends AbstractModule
{
    public function key(): string
    {
        return 'cross_dock';
    }
}
