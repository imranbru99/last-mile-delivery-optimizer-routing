<?php

declare(strict_types=1);

namespace App\Modules\Ticketing;

use App\Modules\AbstractModule;

final class TicketingModule extends AbstractModule
{
    public function key(): string
    {
        return 'ticketing';
    }
}
