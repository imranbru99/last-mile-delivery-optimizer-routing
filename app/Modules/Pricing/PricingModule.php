<?php

declare(strict_types=1);

namespace App\Modules\Pricing;

use App\Modules\AbstractModule;

final class PricingModule extends AbstractModule
{
    public function key(): string
    {
        return 'pricing';
    }
}
