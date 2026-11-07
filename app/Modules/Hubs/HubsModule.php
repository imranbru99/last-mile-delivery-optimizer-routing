<?php

declare(strict_types=1);

namespace App\Modules\Hubs;

use App\Modules\AbstractModule;

final class HubsModule extends AbstractModule
{
    public function key(): string
    {
        return 'hubs';
    }
}
