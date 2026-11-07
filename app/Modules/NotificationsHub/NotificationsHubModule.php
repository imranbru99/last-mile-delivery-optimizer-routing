<?php

declare(strict_types=1);

namespace App\Modules\NotificationsHub;

use App\Modules\AbstractModule;

final class NotificationsHubModule extends AbstractModule
{
    public function key(): string
    {
        return 'notifications_hub';
    }
}
