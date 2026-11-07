<?php

declare(strict_types=1);

namespace App\Modules\BatchingWaves;

use App\Modules\AbstractModule;

final class BatchingWavesModule extends AbstractModule
{
    public function key(): string
    {
        return 'batching_waves';
    }
}
