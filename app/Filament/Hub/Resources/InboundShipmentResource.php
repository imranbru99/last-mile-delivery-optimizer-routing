<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Hub\Resources\InboundShipmentResource\Pages\EditInboundShipment;
use App\Filament\Hub\Resources\InboundShipmentResource\Pages\ListInboundShipments;
use App\Models\Shipment;
use Filament\Actions\EditAction;
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

class InboundShipmentResource extends Resource
{
    use ModuleAware;

    public static string $module = 'hubs';

    protected static ?string $model = Shipment::class;

    protected static ?string $slug = 'inbound-shipments';

    protected static ?string $navigationLabel = 'Inbound shipments';

    protected static ?string $modelLabel = 'Inbound shipment';

    protected static ?string $pluralModelLabel = 'Inbound shipments';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static ?int $navigationSort = 30;

    /**
     * @return array<string, string>
     */
    public static function inboundStatusOptions(): array
    {
        return [
            'inbound' => 'Inbound',
            'at_hub' => 'At hub',
            'received' => 'Received',
            'sorting' => 'Sorting',
            'ready_for_loadout' => 'Ready for load-out',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Inbound handling')->schema([
                TextInput::make('tracking_code')->disabled(),
                Select::make('status')
                    ->options(self::inboundStatusOptions())
                    ->required(),
                Select::make('hub_id')
                    ->relationship('hub', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('pieces')->numeric(),
                TextInput::make('weight_kg')->numeric(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_code')->searchable()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('hub.name')->label('Hub')->sortable(),
                TextColumn::make('pieces')->sortable(),
                TextColumn::make('weight_kg')->numeric(2)->sortable(),
                TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::inboundStatusOptions()),
                SelectFilter::make('hub_id')->relationship('hub', 'name')->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInboundShipments::route('/'),
            'edit' => EditInboundShipment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('hub_id')
            ->whereIn('status', array_keys(self::inboundStatusOptions()));
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
