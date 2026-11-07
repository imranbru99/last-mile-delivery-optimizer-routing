<?php

declare(strict_types=1);

namespace App\Modules\EtaPrediction;

use App\Modules\AbstractModule;

final class EtaPredictionModule extends AbstractModule
{
    public function key(): string
    {
        return 'eta_prediction';
    }
}
