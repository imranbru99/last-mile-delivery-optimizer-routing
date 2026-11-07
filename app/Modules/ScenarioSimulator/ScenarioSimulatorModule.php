<?php

declare(strict_types=1);

namespace App\Modules\ScenarioSimulator;

use App\Modules\AbstractModule;

final class ScenarioSimulatorModule extends AbstractModule
{
    public function key(): string
    {
        return 'scenario_simulator';
    }
}
