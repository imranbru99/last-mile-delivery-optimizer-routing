<?php

declare(strict_types=1);

namespace App\Modules\MultiLeg;

use App\Modules\AbstractModule;

final class MultiLegModule extends AbstractModule
{
    public function key(): string
    {
        return 'multi_leg';
    }
}
