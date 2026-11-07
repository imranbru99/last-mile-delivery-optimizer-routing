<?php

declare(strict_types=1);

namespace App\Modules\Automation;

use App\Modules\AbstractModule;

final class AutomationModule extends AbstractModule
{
    public function key(): string
    {
        return 'automation';
    }
}
