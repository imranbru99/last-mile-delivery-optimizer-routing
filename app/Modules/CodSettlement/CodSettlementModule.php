<?php

declare(strict_types=1);

namespace App\Modules\CodSettlement;

use App\Modules\AbstractModule;

final class CodSettlementModule extends AbstractModule
{
    public function key(): string
    {
        return 'cod_settlement';
    }
}
