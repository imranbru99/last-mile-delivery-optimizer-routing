<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Finance\Resources\CodSettlementResource\Pages\CreateCodSettlement;
use App\Filament\Finance\Resources\CodSettlementResource\Pages\EditCodSettlement;
use App\Filament\Finance\Resources\CodSettlementResource\Pages\ListCodSettlements;
use App\Models\CodSettlement;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CodSettlementResource extends Resource
{
    use ModuleAware;

    public static string $module = 'cod_settlement';

    protected static ?string $model = CodSettlement::class;

    protected static ?string $navigationLabel = 'COD Settlements';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyBangladeshi;

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('shipment_id')->relationship('shipment', 'tracking_code')->required()->searchable(),
            Select::make('driver_id')->relationship('driver', 'employee_code')->searchable(),
            TextInput::make('amount')->numeric()->required(),
            TextInput::make('currency')->default('BDT')->maxLength(3),
            Select::make('status')->options([
                'pending' => 'Pending',
                'collected' => 'Collected',
                'remitted' => 'Remitted',
            ])->required(),
            DateTimePicker::make('settled_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shipment.tracking_code')->label('Shipment')->searchable(),
                TextColumn::make('amount')->money(fn (CodSettlement $record) => $record->currency),
                TextColumn::make('status')->badge(),
                TextColumn::make('settled_at')->dateTime(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCodSettlements::route('/'),
            'create' => CreateCodSettlement::route('/create'),
            'edit' => EditCodSettlement::route('/{record}/edit'),
        ];
    }
}
