<?php

declare(strict_types=1);

namespace App\Modules\DynamicReopt;

use App\Modules\AbstractModule;

final class DynamicReoptModule extends AbstractModule
{
    public function key(): string
    {
        return 'dynamic_reopt';
    }
}
