<?php

declare(strict_types=1);

namespace App\Modules\I18n;

use App\Modules\AbstractModule;

final class I18nModule extends AbstractModule
{
    public function key(): string
    {
        return 'i18n';
    }
}
