<?php

declare(strict_types=1);

namespace App\Modules\NotificationsCenter;

use App\Modules\AbstractModule;

final class NotificationsCenterModule extends AbstractModule
{
    public function key(): string
    {
        return 'notifications_center';
    }
}
