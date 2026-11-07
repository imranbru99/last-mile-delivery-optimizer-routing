<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class MaintenanceSchedule extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'maintenance';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Maintenance schedule';

    protected static ?string $title = 'Vehicles needing attention';

    protected static ?int $navigationSort = 40;

    protected string $view = 'filament.fleet.pages.maintenance-schedule';

    public function table(Table $table): Table
    {
        $attentionVehicleIds = MaintenanceLog::query()
            ->whereIn('status', ['scheduled', 'overdue'])
            ->where(function (Builder $query): void {
                $query
                    ->where('status', 'overdue')
                    ->orWhere('due_at', '<=', Carbon::now()->addDays(14));
            })
            ->pluck('vehicle_id');

        return $table
            ->query(
                Vehicle::query()
                    ->with('hub')
                    ->where(function (Builder $query) use ($attentionVehicleIds): void {
                        $query
                            ->whereIn('status', ['maintenance', 'out_of_service'])
                            ->orWhereIn('id', $attentionVehicleIds);
                    })
            )
            ->columns([
                TextColumn::make('plate_number')->label('Plate')->searchable(),
                TextColumn::make('name'),
                TextColumn::make('status')->badge(),
                TextColumn::make('hub.name')->label('Hub')->toggleable(),
                TextColumn::make('documents.insurance_expires_at')
                    ->date()
                    ->label('Insurance exp.')
                    ->color(function (Vehicle $record): ?string {
                        $expires = data_get($record->documents, 'insurance_expires_at');
                        if (! $expires) {
                            return null;
                        }

                        return Carbon::parse($expires)->isPast() ? 'danger' : null;
                    }),
                TextColumn::make('next_maintenance')
                    ->label('Next maintenance')
                    ->state(function (Vehicle $record): ?string {
                        $next = MaintenanceLog::query()
                            ->where('vehicle_id', $record->id)
                            ->whereIn('status', ['scheduled', 'overdue'])
                            ->orderBy('due_at')
                            ->value('due_at');

                        return $next ? Carbon::parse($next)->toDateString() : null;
                    }),
            ])
            ->defaultSort('plate_number');
    }
}
