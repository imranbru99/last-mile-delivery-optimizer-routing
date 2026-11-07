<?php

declare(strict_types=1);

namespace App\Modules\Profitability;

use App\Modules\AbstractModule;

final class ProfitabilityModule extends AbstractModule
{
    public function key(): string
    {
        return 'profitability';
    }
}
