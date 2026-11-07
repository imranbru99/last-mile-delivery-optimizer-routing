<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Fleet\Resources\DriverResource\Pages\CreateDriver;
use App\Filament\Fleet\Resources\DriverResource\Pages\EditDriver;
use App\Filament\Fleet\Resources\DriverResource\Pages\ListDrivers;
use App\Models\DriverProfile;
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

class DriverResource extends Resource
{
    use ModuleAware;

    public static string $module = 'drivers';

    protected static ?string $model = DriverProfile::class;

    protected static ?string $navigationLabel = 'Drivers';

    protected static ?string $modelLabel = 'Driver';

    protected static ?string $pluralModelLabel = 'Drivers';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Driver profile')->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('employee_code')->required()->maxLength(64),
                TextInput::make('license_number')->maxLength(64),
                DatePicker::make('license_expires_at'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'offboarded' => 'Offboarded',
                    ])
                    ->required()
                    ->default('active'),
                TextInput::make('rating')->numeric()->default(5)->minValue(0)->maxValue(5)->step(0.01),
                Select::make('hub_id')
                    ->relationship('hub', 'name')
                    ->searchable()
                    ->required(),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->searchable(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_code')->searchable()->sortable(),
                TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('rating')->numeric(2)->sortable(),
                TextColumn::make('license_number')->toggleable(),
                TextColumn::make('license_expires_at')->date()->sortable(),
                TextColumn::make('hub.name')->label('Hub')->toggleable(),
                TextColumn::make('vehicle.plate_number')->label('Vehicle')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'offboarded' => 'Offboarded',
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
            'index' => ListDrivers::route('/'),
            'create' => CreateDriver::route('/create'),
            'edit' => EditDriver::route('/{record}/edit'),
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
