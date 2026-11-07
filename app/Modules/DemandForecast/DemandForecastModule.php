<?php

declare(strict_types=1);

namespace App\Modules\DemandForecast;

use App\Modules\AbstractModule;

final class DemandForecastModule extends AbstractModule
{
    public function key(): string
    {
        return 'demand_forecast';
    }
}
