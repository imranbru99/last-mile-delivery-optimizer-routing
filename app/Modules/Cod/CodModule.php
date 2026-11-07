<?php

declare(strict_types=1);

namespace App\Modules\Cod;

use App\Modules\AbstractModule;

final class CodModule extends AbstractModule
{
    public function key(): string
    {
        return 'cod';
    }
}
