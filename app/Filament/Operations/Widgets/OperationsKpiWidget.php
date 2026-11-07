<?php

declare(strict_types=1);

namespace App\Filament\Operations\Widgets;

use App\Services\Dashboards\AdminPanelKpiBuilder;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationsKpiWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Operations snapshot';

    public static function canView(): bool
    {
        return app(ModuleManager::class)->isEnabled('dashboards');
    }

    protected function getStats(): array
    {
        $business = app(TenantContext::class)->business() ?? auth()->user()?->business;
        if (! $business) {
            return [
                Stat::make('Business', '—')->description('Sign in with a tenant user'),
            ];
        }

        return collect(app(AdminPanelKpiBuilder::class)->forBusiness($business))
            ->map(fn (array $card): Stat => Stat::make($card['label'], (string) $card['value'])
                ->description($card['hint'] ?? null))
            ->all();
    }
}
