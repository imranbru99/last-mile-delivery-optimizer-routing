<?php

declare(strict_types=1);

namespace App\Modules\SystemHealth;

use App\Modules\AbstractModule;

final class SystemHealthModule extends AbstractModule
{
    public function key(): string
    {
        return 'system_health';
    }
}
