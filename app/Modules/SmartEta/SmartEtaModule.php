<?php

declare(strict_types=1);

namespace App\Modules\SmartEta;

use App\Modules\AbstractModule;

final class SmartEtaModule extends AbstractModule
{
    public function key(): string
    {
        return 'smart_eta';
    }
}
