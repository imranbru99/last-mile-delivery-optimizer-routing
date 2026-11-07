<?php

declare(strict_types=1);

namespace App\Modules\GigMarketplace;

use App\Modules\AbstractModule;

final class GigMarketplaceModule extends AbstractModule
{
    public function key(): string
    {
        return 'gig_marketplace';
    }
}
