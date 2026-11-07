<?php

declare(strict_types=1);

namespace App\Modules\Feedback;

use App\Modules\AbstractModule;

final class FeedbackModule extends AbstractModule
{
    public function key(): string
    {
        return 'feedback';
    }
}
