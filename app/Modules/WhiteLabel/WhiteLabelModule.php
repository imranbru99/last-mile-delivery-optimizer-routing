<?php

declare(strict_types=1);

namespace App\Modules\WhiteLabel;

use App\Modules\AbstractModule;

final class WhiteLabelModule extends AbstractModule
{
    public function key(): string
    {
        return 'white_label';
    }
}
