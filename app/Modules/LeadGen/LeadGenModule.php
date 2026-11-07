<?php

declare(strict_types=1);

namespace App\Modules\LeadGen;

use App\Modules\AbstractModule;

final class LeadGenModule extends AbstractModule
{
    public function key(): string
    {
        return 'lead_gen';
    }
}
