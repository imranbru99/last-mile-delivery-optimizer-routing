<?php

declare(strict_types=1);

namespace App\Modules\EvManagement;

use App\Modules\AbstractModule;

final class EvManagementModule extends AbstractModule
{
    public function key(): string
    {
        return 'ev_management';
    }
}
