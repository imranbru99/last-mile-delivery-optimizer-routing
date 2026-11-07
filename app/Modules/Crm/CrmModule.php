<?php

declare(strict_types=1);

namespace App\Modules\Crm;

use App\Modules\AbstractModule;

final class CrmModule extends AbstractModule
{
    public function key(): string
    {
        return 'crm';
    }
}
