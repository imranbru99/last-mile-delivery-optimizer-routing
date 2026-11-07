<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Business;
use App\Models\BusinessModule;
use App\Models\Shipment;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Platform overview';

    public static function canView(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    protected function getStats(): array
    {
        $businessCount = Business::query()->count();
        $userCount = User::query()->count();
        $shipmentCount = Shipment::query()->count();

        $enabledModules = BusinessModule::query()->where('is_enabled', true)->count();
        $modulesPerBusiness = $businessCount > 0
            ? round($enabledModules / $businessCount, 1)
            : 0.0;

        return [
            Stat::make('Businesses', (string) $businessCount)
                ->description('Active tenants on the platform')
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->color('primary'),
            Stat::make('Users', (string) $userCount)
                ->description('All panel accounts')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('success'),
            Stat::make('Shipments', (string) $shipmentCount)
                ->description('Across all tenants')
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->color('warning'),
            Stat::make('Modules enabled (avg)', (string) $modulesPerBusiness)
                ->description('Per business · '.$enabledModules.' total toggles on')
                ->descriptionIcon(Heroicon::OutlinedPuzzlePiece)
                ->color('info'),
        ];
    }
}
