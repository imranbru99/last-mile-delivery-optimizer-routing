<?php

declare(strict_types=1);

namespace App\Modules\AiSupportBot;

use App\Modules\AbstractModule;

final class AiSupportBotModule extends AbstractModule
{
    public function key(): string
    {
        return 'ai_support_bot';
    }
}
