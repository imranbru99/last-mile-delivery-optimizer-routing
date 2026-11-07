<?php

declare(strict_types=1);

namespace App\Modules\Assets;

use App\Modules\AbstractModule;

final class AssetsModule extends AbstractModule
{
    public function key(): string
    {
        return 'assets';
    }
}
