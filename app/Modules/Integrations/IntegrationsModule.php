<?php

declare(strict_types=1);

namespace App\Modules\Integrations;

use App\Modules\AbstractModule;

final class IntegrationsModule extends AbstractModule
{
    public function key(): string
    {
        return 'integrations';
    }
}
