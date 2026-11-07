<?php

declare(strict_types=1);

namespace App\Modules\ApiTokens;

use App\Modules\AbstractModule;

final class ApiTokensModule extends AbstractModule
{
    public function key(): string
    {
        return 'api_tokens';
    }
}
