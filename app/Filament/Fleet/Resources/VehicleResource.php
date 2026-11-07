<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Fleet\Resources\VehicleResource\Pages\CreateVehicle;
use App\Filament\Fleet\Resources\VehicleResource\Pages\EditVehicle;
use App\Filament\Fleet\Resources\VehicleResource\Pages\ListVehicles;
use App\Models\Vehicle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    use ModuleAware;

    public static string $module = 'fleet';

    protected static ?string $model = Vehicle::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $navigationLabel = 'Vehicles';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Vehicle')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('plate_number')->label('Plate')->required()->maxLength(32),
                Select::make('type')
                    ->options([
                        'bike' => 'Bike',
                        'van' => 'Van',
                        'truck' => 'Truck',
                        'car' => 'Car',
                    ])
                    ->required()
                    ->default('van'),
                Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'assigned' => 'Assigned',
                        'maintenance' => 'Maintenance',
                        'out_of_service' => 'Out of service',
                    ])
                    ->required()
                    ->default('available'),
                Select::make('hub_id')
                    ->relationship('hub', 'name')
                    ->searchable(),
                TextInput::make('capacity_weight_kg')->numeric()->label('Capacity weight (kg)'),
                TextInput::make('capacity_volume_m3')->numeric()->label('Capacity volume (m³)'),
                TextInput::make('capacity_stops')->numeric()->default(50),
            ])->columns(2),
            Section::make('Document expiry')->schema([
                DatePicker::make('documents.registration_expires_at')
                    ->label('Registration expires'),
                DatePicker::make('documents.insurance_expires_at')
                    ->label('Insurance expires'),
                DatePicker::make('documents.fitness_expires_at')
                    ->label('Fitness certificate expires'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plate_number')->label('Plate')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('hub.name')->label('Hub')->toggleable(),
                TextColumn::make('capacity_weight_kg')->numeric(2)->toggleable(),
                TextColumn::make('capacity_volume_m3')->numeric(2)->toggleable(),
                TextColumn::make('documents.insurance_expires_at')->date()->label('Insurance exp.')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'assigned' => 'Assigned',
                        'maintenance' => 'Maintenance',
                        'out_of_service' => 'Out of service',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'bike' => 'Bike',
                        'van' => 'Van',
                        'truck' => 'Truck',
                        'car' => 'Car',
                    ]),
            ])
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
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'edit' => EditVehicle::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
