<?php

declare(strict_types=1);

namespace App\Modules\AiCopilot;

use App\Modules\AbstractModule;

final class AiCopilotModule extends AbstractModule
{
    public function key(): string
    {
        return 'ai_copilot';
    }
}
