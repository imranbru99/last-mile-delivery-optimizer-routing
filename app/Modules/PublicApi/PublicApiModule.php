<?php

declare(strict_types=1);

namespace App\Modules\PublicApi;

use App\Modules\AbstractModule;

final class PublicApiModule extends AbstractModule
{
    public function key(): string
    {
        return 'public_api';
    }
}
