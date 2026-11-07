<?php

declare(strict_types=1);

namespace App\Modules\DispatchBoard;

use App\Modules\AbstractModule;

final class DispatchBoardModule extends AbstractModule
{
    public function key(): string
    {
        return 'dispatch_board';
    }
}
