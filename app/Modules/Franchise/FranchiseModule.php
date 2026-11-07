<?php

declare(strict_types=1);

namespace App\Modules\Franchise;

use App\Modules\AbstractModule;

final class FranchiseModule extends AbstractModule
{
    public function key(): string
    {
        return 'franchise';
    }
}
