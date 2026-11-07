<?php

declare(strict_types=1);

namespace App\Modules\Pickups;

use App\Modules\AbstractModule;

final class PickupsModule extends AbstractModule
{
    public function key(): string
    {
        return 'pickups';
    }
}
