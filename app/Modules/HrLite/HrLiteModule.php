<?php

declare(strict_types=1);

namespace App\Modules\HrLite;

use App\Modules\AbstractModule;

final class HrLiteModule extends AbstractModule
{
    public function key(): string
    {
        return 'hr_lite';
    }
}
