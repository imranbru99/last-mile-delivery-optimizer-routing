<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Finance\Resources\RateCardResource\Pages\CreateRateCard;
use App\Filament\Finance\Resources\RateCardResource\Pages\EditRateCard;
use App\Filament\Finance\Resources\RateCardResource\Pages\ListRateCards;
use App\Models\RateCard;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RateCardResource extends Resource
{
    use ModuleAware;

    public static string $module = 'pricing';

    protected static ?string $model = RateCard::class;

    protected static ?string $navigationLabel = 'Rate Cards';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->required()->maxLength(64),
                TextInput::make('currency')->default('BDT')->maxLength(3),
                TextInput::make('base_fee')->numeric()->default(0),
                TextInput::make('per_kg_fee')->numeric()->default(0),
                Textarea::make('weight_tiers')
                    ->helperText('JSON array of weight tiers')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->columnSpanFull(),
                Toggle::make('is_active')->default(true),
                DatePicker::make('valid_from'),
                DatePicker::make('valid_until'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('base_fee')->money(fn (RateCard $record) => $record->currency),
                TextColumn::make('per_kg_fee')->label('Per kg'),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->since(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRateCards::route('/'),
            'create' => CreateRateCard::route('/create'),
            'edit' => EditRateCard::route('/{record}/edit'),
        ];
    }
}
