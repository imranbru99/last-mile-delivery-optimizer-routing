<?php

declare(strict_types=1);

namespace App\Modules\Loyalty;

use App\Modules\AbstractModule;

final class LoyaltyModule extends AbstractModule
{
    public function key(): string
    {
        return 'loyalty';
    }
}
