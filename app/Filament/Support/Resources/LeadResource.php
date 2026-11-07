<?php

declare(strict_types=1);

namespace App\Filament\Support\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Support\Resources\LeadResource\Pages\EditLead;
use App\Filament\Support\Resources\LeadResource\Pages\ListLeads;
use App\Models\Lead;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    use ModuleAware;

    public static string $module = 'crm';

    protected static ?string $model = Lead::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $navigationLabel = 'Leads';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->maxLength(255),
            TextInput::make('phone')->maxLength(64),
            TextInput::make('source')->maxLength(64),
            Select::make('status')->options([
                'new' => 'New',
                'contacted' => 'Contacted',
                'qualified' => 'Qualified',
                'lost' => 'Lost',
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('phone')->toggleable(),
                TextColumn::make('source')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
