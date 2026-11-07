<?php

declare(strict_types=1);

namespace App\Filament\Auditor\Resources;

use App\Filament\Auditor\Resources\AuditShipmentResource\Pages\ListAuditShipments;
use App\Filament\Auditor\Resources\AuditShipmentResource\Pages\ViewAuditShipment;
use App\Filament\Concerns\ModuleAware;
use App\Models\Shipment;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class AuditShipmentResource extends Resource
{
    use ModuleAware;

    public static string $module = 'compliance';

    protected static ?string $model = Shipment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationLabel = 'Shipments (read-only)';

    protected static ?int $navigationSort = 10;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('tracking_code'),
            TextEntry::make('status')->badge(),
            TextEntry::make('service_level'),
            TextEntry::make('weight_kg'),
            TextEntry::make('cod_amount'),
            TextEntry::make('deliveryAddress.line1')->label('Delivery'),
            TextEntry::make('created_at')->dateTime(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_code')->searchable()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('service_level'),
                TextColumn::make('cod_amount')->numeric(2),
                TextColumn::make('created_at')->since(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'delivered' => 'Delivered',
                    'failed' => 'Failed',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditShipments::route('/'),
            'view' => ViewAuditShipment::route('/{record}'),
        ];
    }
}
