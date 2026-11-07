<?php

declare(strict_types=1);

namespace App\Modules\DeliveryPreferences;

use App\Modules\AbstractModule;

final class DeliveryPreferencesModule extends AbstractModule
{
    public function key(): string
    {
        return 'delivery_preferences';
    }
}
