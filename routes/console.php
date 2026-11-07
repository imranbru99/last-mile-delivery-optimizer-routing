<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('demo:reset')->hourly()->when(
    fn (): bool => app()->environment(['local', 'demo'])
);

Schedule::command('demo:simulate --loops=1')->everyMinute()->when(
    fn (): bool => app()->environment(['local', 'demo'])
);
