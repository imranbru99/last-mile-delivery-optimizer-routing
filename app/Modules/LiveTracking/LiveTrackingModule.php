<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking;

use App\Modules\AbstractModule;

final class LiveTrackingModule extends AbstractModule
{
    public function key(): string
    {
        return 'live_tracking';
    }
}
