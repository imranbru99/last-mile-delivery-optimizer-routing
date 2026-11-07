<?php

declare(strict_types=1);

namespace App\Modules\Chat;

use App\Modules\AbstractModule;

final class ChatModule extends AbstractModule
{
    public function key(): string
    {
        return 'chat';
    }
}
