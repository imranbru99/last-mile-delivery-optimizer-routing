<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Widgets;

use App\Filament\Concerns\ModuleAware;
use App\Models\DriverProfile;
use App\Models\FuelLog;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class FleetStatsOverview extends StatsOverviewWidget
{
    use ModuleAware;

    public static string $module = 'fleet';

    protected static ?int $sort = 1;

    protected ?string $heading = 'Fleet overview';

    public static function canView(): bool
    {
        return parent::canView() && static::moduleIsEnabled();
    }

    protected function getStats(): array
    {
        $available = Vehicle::query()->where('status', 'available')->count();
        $inMaintenance = Vehicle::query()->whereIn('status', ['maintenance', 'out_of_service'])->count();
        $activeDrivers = DriverProfile::query()->where('status', 'active')->count();
        $fuelThisMonth = FuelLog::query()
            ->where('filled_at', '>=', Carbon::now()->startOfMonth())
            ->sum('liters');
        $overdueMaintenance = MaintenanceLog::query()
            ->where('status', 'overdue')
            ->count();

        return [
            Stat::make('Available vehicles', (string) $available)
                ->icon(Heroicon::OutlinedTruck),
            Stat::make('Active drivers', (string) $activeDrivers)
                ->icon(Heroicon::OutlinedIdentification),
            Stat::make('Fuel this month (L)', number_format((float) $fuelThisMonth, 1))
                ->icon(Heroicon::OutlinedFire),
            Stat::make('Needs attention', (string) ($inMaintenance + $overdueMaintenance))
                ->description('Maintenance / out of service / overdue')
                ->color(($inMaintenance + $overdueMaintenance) > 0 ? 'warning' : 'success')
                ->icon(Heroicon::OutlinedWrenchScrewdriver),
        ];
    }
}
