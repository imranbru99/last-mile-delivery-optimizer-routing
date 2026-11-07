<?php

declare(strict_types=1);

namespace App\Modules\AiAddress;

use App\Modules\AbstractModule;

final class AiAddressModule extends AbstractModule
{
    public function key(): string
    {
        return 'ai_address';
    }
}
