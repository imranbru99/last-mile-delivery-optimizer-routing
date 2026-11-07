<?php

declare(strict_types=1);

namespace App\Modules\Sustainability;

use App\Modules\AbstractModule;

final class SustainabilityModule extends AbstractModule
{
    public function key(): string
    {
        return 'sustainability';
    }
}
