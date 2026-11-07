<?php

declare(strict_types=1);

namespace App\Modules\ColdChain;

use App\Modules\AbstractModule;

final class ColdChainModule extends AbstractModule
{
    public function key(): string
    {
        return 'cold_chain';
    }
}
