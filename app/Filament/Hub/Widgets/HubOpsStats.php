<?php

declare(strict_types=1);

namespace App\Filament\Hub\Widgets;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Hub\Resources\InboundShipmentResource;
use App\Models\DeliveryRoute;
use App\Models\Hub;
use App\Models\Shipment;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class HubOpsStats extends StatsOverviewWidget
{
    use ModuleAware;

    public static string $module = 'hubs';

    protected static ?int $sort = 1;

    protected ?string $heading = 'Hub operations';

    public static function canView(): bool
    {
        return parent::canView() && static::moduleIsEnabled();
    }

    protected function getStats(): array
    {
        $inboundStatuses = array_keys(InboundShipmentResource::inboundStatusOptions());

        $activeHubs = Hub::query()->where('is_active', true)->count();
        $inboundToday = Shipment::query()
            ->whereNotNull('hub_id')
            ->whereIn('status', $inboundStatuses)
            ->whereDate('updated_at', Carbon::today())
            ->count();
        $loadOutToday = DeliveryRoute::query()
            ->whereDate('planned_date', Carbon::today())
            ->whereIn('status', ['planned', 'dispatched', 'in_progress'])
            ->count();

        return [
            Stat::make('Active hubs', (string) $activeHubs)
                ->description('Operational hub locations')
                ->icon(Heroicon::OutlinedBuildingStorefront),
            Stat::make('Inbound queue', (string) $inboundToday)
                ->description('Touched at hub today')
                ->icon(Heroicon::OutlinedArrowDownTray),
            Stat::make('Load-outs today', (string) $loadOutToday)
                ->description('Routes planned or en route')
                ->icon(Heroicon::OutlinedTruck),
        ];
    }
}
