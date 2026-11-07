<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Widgets;

use App\Services\Performance\DrutogotiHealthMetrics;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DrutogotiSystemHealthWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Platform performance (Drutogoti)';

    protected function getStats(): array
    {
        return collect(app(DrutogotiHealthMetrics::class)->snapshot())
            ->map(fn (array $row): Stat => Stat::make($row['label'], (string) $row['value'])
                ->description($row['hint'] ?? null))
            ->all();
    }
}
