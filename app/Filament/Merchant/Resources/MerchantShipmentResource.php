<?php

declare(strict_types=1);

namespace App\Filament\Merchant\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Merchant\Resources\MerchantShipmentResource\Pages\CreateMerchantShipment;
use App\Filament\Merchant\Resources\MerchantShipmentResource\Pages\EditMerchantShipment;
use App\Filament\Merchant\Resources\MerchantShipmentResource\Pages\ListMerchantShipments;
use App\Models\Shipment;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MerchantShipmentResource extends Resource
{
    use ModuleAware;

    public static string $module = 'merchant_portal';

    protected static ?string $model = Shipment::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $navigationLabel = 'My Shipments';

    protected static ?string $modelLabel = 'Shipment';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Create shipment')->schema([
                TextInput::make('tracking_code')
                    ->default(fn (): string => 'MRC-'.strtoupper(Str::random(8)))
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
                    ->default('pending')
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
                TextInput::make('weight_kg')->numeric()->default(1),
                TextInput::make('volume_m3')->numeric()->default(0.01),
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
                TextColumn::make('deliveryAddress.city')->label('City'),
                TextColumn::make('cod_amount')->numeric(2),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'in_transit' => 'In transit',
                    'delivered' => 'Delivered',
                    'failed' => 'Failed',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user !== null) {
            $query->where('merchant_user_id', $user->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMerchantShipments::route('/'),
            'create' => CreateMerchantShipment::route('/create'),
            'edit' => EditMerchantShipment::route('/{record}/edit'),
        ];
    }
}
