<?php

declare(strict_types=1);

namespace App\Filament\Operations\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\DeliveryRoute;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DispatchBoard extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'dispatch_board';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Dispatch board';

    protected static ?string $title = 'Dispatch board';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.operations.pages.dispatch-board';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DeliveryRoute::query()
                    ->with(['driver', 'vehicle'])
                    ->whereIn('status', ['draft', 'planned', 'dispatched', 'in_progress'])
            )
            ->columns([
                TextColumn::make('code')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('planned_date')->date(),
                TextColumn::make('driver.employee_code')->label('Driver'),
                TextColumn::make('vehicle.plate_number')->label('Vehicle'),
                TextColumn::make('total_stops'),
                TextColumn::make('updated_at')->since(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'planned' => 'Planned',
                        'dispatched' => 'Dispatched',
                        'in_progress' => 'In progress',
                    ]),
            ])
            ->defaultSort('planned_date', 'desc')
            ->poll('30s');
    }
}
