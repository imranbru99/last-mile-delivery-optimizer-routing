<?php

declare(strict_types=1);

namespace App\Modules\MultiCurrency;

use App\Modules\AbstractModule;

final class MultiCurrencyModule extends AbstractModule
{
    public function key(): string
    {
        return 'multi_currency';
    }
}
