<?php

declare(strict_types=1);

namespace App\Modules\AnomalyFraud;

use App\Modules\AbstractModule;

final class AnomalyFraudModule extends AbstractModule
{
    public function key(): string
    {
        return 'anomaly_fraud';
    }
}
