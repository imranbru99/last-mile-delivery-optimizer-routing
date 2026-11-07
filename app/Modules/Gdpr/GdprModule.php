<?php

declare(strict_types=1);

namespace App\Modules\Gdpr;

use App\Modules\AbstractModule;

final class GdprModule extends AbstractModule
{
    public function key(): string
    {
        return 'gdpr';
    }
}
