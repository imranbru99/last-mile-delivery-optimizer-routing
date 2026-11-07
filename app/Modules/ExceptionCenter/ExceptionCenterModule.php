<?php

declare(strict_types=1);

namespace App\Modules\ExceptionCenter;

use App\Modules\AbstractModule;

final class ExceptionCenterModule extends AbstractModule
{
    public function key(): string
    {
        return 'exception_center';
    }
}
