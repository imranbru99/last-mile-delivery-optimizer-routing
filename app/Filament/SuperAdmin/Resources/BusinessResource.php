<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\Concerns\SuperAdminOnly;
use App\Filament\SuperAdmin\Resources\BusinessResource\Pages\CreateBusiness;
use App\Filament\SuperAdmin\Resources\BusinessResource\Pages\EditBusiness;
use App\Filament\SuperAdmin\Resources\BusinessResource\Pages\ListBusinesses;
use App\Models\Business;
use App\Support\Modules\ModuleManager;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessResource extends Resource
{
    use SuperAdminOnly;

    protected static ?string $model = Business::class;

    protected static ?string $navigationLabel = 'Businesses';

    protected static ?string $modelLabel = 'Business';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        $presetOptions = collect(app(ModuleManager::class)->presets())
            ->mapWithKeys(fn (array $preset, string $key): array => [$key => $preset['name']])
            ->all();

        return $schema->components([
            Section::make('Business profile')->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('business_type')
                    ->label('Industry preset')
                    ->options($presetOptions)
                    ->required()
                    ->searchable(),
                Toggle::make('is_active')->default(true),
            ])->columns(2),
            Section::make('Branding & locale')->schema([
                TextInput::make('primary_color')
                    ->label('Primary color')
                    ->default('#0F766E')
                    ->maxLength(32),
                TextInput::make('secondary_color')
                    ->label('Secondary color')
                    ->default('#134E4A')
                    ->maxLength(32),
                TextInput::make('locale')
                    ->default('en')
                    ->maxLength(10),
                TextInput::make('currency')
                    ->default('BDT')
                    ->maxLength(3),
                TextInput::make('timezone')
                    ->default('Asia/Dhaka')
                    ->maxLength(64),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        $presetOptions = collect(app(ModuleManager::class)->presets())
            ->mapWithKeys(fn (array $preset, string $key): array => [$key => $preset['name']])
            ->all();

        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->toggleable(),
                TextColumn::make('business_type')
                    ->label('Preset')
                    ->formatStateUsing(fn (?string $state): string => $presetOptions[$state] ?? ($state ?? '—'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('locale')->toggleable(),
                TextColumn::make('currency')->toggleable(),
                TextColumn::make('timezone')->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')->boolean()->sortable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->sortable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
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
            'index' => ListBusinesses::route('/'),
            'create' => CreateBusiness::route('/create'),
            'edit' => EditBusiness::route('/{record}/edit'),
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
