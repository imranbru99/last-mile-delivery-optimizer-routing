<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Fleet\Resources\FuelLogResource;
use App\Models\FuelLog as FuelLogModel;
use Filament\Actions\CreateAction;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class FuelLog extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'fuel';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $navigationLabel = 'Fuel log';

    protected static ?string $title = 'Fuel log';

    protected static ?int $navigationSort = 50;

    protected string $view = 'filament.fleet.pages.fuel-log';

    public function table(Table $table): Table
    {
        return $table
            ->query(FuelLogModel::query()->with('vehicle'))
            ->columns([
                TextColumn::make('filled_at')->dateTime()->sortable(),
                TextColumn::make('vehicle.plate_number')->label('Vehicle')->searchable(),
                TextColumn::make('liters')->numeric(2)->sortable(),
                TextColumn::make('cost')->money('BDT')->sortable(),
                TextColumn::make('odometer')->toggleable(),
                TextColumn::make('notes')->limit(40)->toggleable(),
            ])
            ->defaultSort('filled_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->url(fn (): string => FuelLogResource::getUrl('create')),
            ])
            ->recordUrl(fn (FuelLogModel $record): string => FuelLogResource::getUrl('edit', ['record' => $record]));
    }
}
