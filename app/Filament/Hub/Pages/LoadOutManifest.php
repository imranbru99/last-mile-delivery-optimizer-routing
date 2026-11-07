<?php

declare(strict_types=1);

namespace App\Filament\Hub\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\DeliveryRoute;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class LoadOutManifest extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'hubs';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Load-out manifest';

    protected static ?string $title = 'Today’s load-out manifest';

    protected static ?int $navigationSort = 40;

    protected string $view = 'filament.hub.pages.load-out-manifest';

    public function table(Table $table): Table
    {
        $today = Carbon::today();

        return $table
            ->query(
                DeliveryRoute::query()
                    ->with(['driver', 'vehicle', 'hub'])
                    ->whereDate('planned_date', $today)
                    ->whereNotNull('driver_id')
                    ->whereIn('status', ['planned', 'dispatched', 'in_progress'])
            )
            ->columns([
                TextColumn::make('code')->searchable(),
                TextColumn::make('name')->toggleable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('hub.name')->label('Hub'),
                TextColumn::make('driver.employee_code')->label('Driver'),
                TextColumn::make('vehicle.plate_number')->label('Vehicle'),
                TextColumn::make('total_stops')->sortable(),
                TextColumn::make('planned_date')->date(),
            ])
            ->defaultSort('code')
            ->poll('30s')
            ->emptyStateHeading('No routes scheduled for load-out today')
            ->emptyStateDescription('Assigned routes for today will appear here for manifest and load-out.');
    }
}
