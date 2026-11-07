<?php

declare(strict_types=1);

namespace App\Modules\Telematics;

use App\Modules\AbstractModule;

final class TelematicsModule extends AbstractModule
{
    public function key(): string
    {
        return 'telematics';
    }
}
