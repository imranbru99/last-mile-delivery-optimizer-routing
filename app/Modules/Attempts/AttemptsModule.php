<?php

declare(strict_types=1);

namespace App\Modules\Attempts;

use App\Modules\AbstractModule;

final class AttemptsModule extends AbstractModule
{
    public function key(): string
    {
        return 'attempts';
    }
}
