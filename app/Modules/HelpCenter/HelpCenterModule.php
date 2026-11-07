<?php

declare(strict_types=1);

namespace App\Modules\HelpCenter;

use App\Modules\AbstractModule;

final class HelpCenterModule extends AbstractModule
{
    public function key(): string
    {
        return 'help_center';
    }
}
