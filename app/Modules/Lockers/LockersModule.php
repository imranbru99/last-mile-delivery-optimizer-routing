<?php

declare(strict_types=1);

namespace App\Modules\Lockers;

use App\Modules\AbstractModule;

final class LockersModule extends AbstractModule
{
    public function key(): string
    {
        return 'lockers';
    }
}
