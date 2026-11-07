<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Fleet\Resources\FuelLogResource\Pages\CreateFuelLog;
use App\Filament\Fleet\Resources\FuelLogResource\Pages\EditFuelLog;
use App\Filament\Fleet\Resources\FuelLogResource\Pages\ListFuelLogs;
use App\Models\FuelLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FuelLogResource extends Resource
{
    use ModuleAware;

    public static string $module = 'fuel';

    protected static ?string $model = FuelLog::class;

    protected static ?string $navigationLabel = 'Fuel logs';

    protected static ?string $slug = 'fuel-logs';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?int $navigationSort = 52;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Fuel entry')->schema([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('liters')->numeric()->required(),
                TextInput::make('cost')->numeric()->default(0),
                TextInput::make('odometer')->numeric(),
                DateTimePicker::make('filled_at')->required()->default(now()),
                Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('filled_at')->dateTime()->sortable(),
                TextColumn::make('vehicle.plate_number')->label('Vehicle')->searchable(),
                TextColumn::make('liters')->numeric(2)->sortable(),
                TextColumn::make('cost')->money('BDT')->sortable(),
                TextColumn::make('odometer')->sortable()->toggleable(),
            ])
            ->defaultSort('filled_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFuelLogs::route('/'),
            'create' => CreateFuelLog::route('/create'),
            'edit' => EditFuelLog::route('/{record}/edit'),
        ];
    }
}
