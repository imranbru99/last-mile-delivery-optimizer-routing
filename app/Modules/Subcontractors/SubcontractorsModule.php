<?php

declare(strict_types=1);

namespace App\Modules\Subcontractors;

use App\Modules\AbstractModule;

final class SubcontractorsModule extends AbstractModule
{
    public function key(): string
    {
        return 'subcontractors';
    }
}
