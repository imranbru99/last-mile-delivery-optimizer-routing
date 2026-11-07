<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Operations\Resources\ShipmentResource\Pages\CreateShipment;
use App\Filament\Operations\Resources\ShipmentResource\Pages\EditShipment;
use App\Filament\Operations\Resources\ShipmentResource\Pages\ListShipments;
use App\Models\Shipment;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    use ModuleAware;

    public static string $module = 'orders';

    protected static ?string $model = Shipment::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Shipments';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Shipment details')->schema([
                TextInput::make('tracking_code')
                    ->required()
                    ->maxLength(64)
                    ->unique(ignoreRecord: true),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'in_transit' => 'In transit',
                        'delivered' => 'Delivered',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                TextInput::make('service_level')->default('standard'),
                TextInput::make('priority')->numeric()->default(5),
                Select::make('pickup_address_id')
                    ->relationship('pickupAddress', 'line1')
                    ->searchable()
                    ->required(),
                Select::make('delivery_address_id')
                    ->relationship('deliveryAddress', 'line1')
                    ->searchable()
                    ->required(),
                TextInput::make('weight_kg')->numeric()->default(0),
                TextInput::make('volume_m3')->numeric()->default(0),
                TextInput::make('pieces')->numeric()->default(1),
                DateTimePicker::make('time_window_start'),
                DateTimePicker::make('time_window_end'),
                TextInput::make('cod_amount')->numeric()->default(0),
                TextInput::make('currency')->default('BDT')->maxLength(3),
                Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_code')->searchable()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('service_level')->toggleable(),
                TextColumn::make('deliveryAddress.city')->label('City')->toggleable(),
                TextColumn::make('weight_kg')->numeric(2)->sortable(),
                TextColumn::make('time_window_end')->dateTime()->toggleable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'in_transit' => 'In transit',
                        'delivered' => 'Delivered',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShipments::route('/'),
            'create' => CreateShipment::route('/create'),
            'edit' => EditShipment::route('/{record}/edit'),
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
