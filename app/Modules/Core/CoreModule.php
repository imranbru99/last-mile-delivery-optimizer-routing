<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Modules\AbstractModule;

final class CoreModule extends AbstractModule
{
    public function key(): string
    {
        return 'core';
    }
}
