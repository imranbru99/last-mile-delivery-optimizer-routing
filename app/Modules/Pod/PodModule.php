<?php

declare(strict_types=1);

namespace App\Modules\Pod;

use App\Modules\AbstractModule;

final class PodModule extends AbstractModule
{
    public function key(): string
    {
        return 'pod';
    }
}
