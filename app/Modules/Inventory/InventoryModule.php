<?php

declare(strict_types=1);

namespace App\Modules\Inventory;

use App\Modules\AbstractModule;

final class InventoryModule extends AbstractModule
{
    public function key(): string
    {
        return 'inventory';
    }
}
