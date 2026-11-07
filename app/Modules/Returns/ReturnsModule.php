<?php

declare(strict_types=1);

namespace App\Modules\Returns;

use App\Modules\AbstractModule;

final class ReturnsModule extends AbstractModule
{
    public function key(): string
    {
        return 'returns';
    }
}
