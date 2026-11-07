<?php

declare(strict_types=1);

namespace App\Modules\AutoAssignment;

use App\Modules\AbstractModule;

final class AutoAssignmentModule extends AbstractModule
{
    public function key(): string
    {
        return 'auto_assignment';
    }
}
