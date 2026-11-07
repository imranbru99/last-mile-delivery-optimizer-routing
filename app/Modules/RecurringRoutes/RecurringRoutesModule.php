<?php

declare(strict_types=1);

namespace App\Modules\RecurringRoutes;

use App\Modules\AbstractModule;

final class RecurringRoutesModule extends AbstractModule
{
    public function key(): string
    {
        return 'recurring_routes';
    }
}
