<?php

declare(strict_types=1);

namespace App\Filament\Support\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Support\Resources\TicketResource\Pages\CreateTicket;
use App\Filament\Support\Resources\TicketResource\Pages\EditTicket;
use App\Filament\Support\Resources\TicketResource\Pages\ListTickets;
use App\Models\Ticket;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    use ModuleAware;

    public static string $module = 'ticketing';

    protected static ?string $model = Ticket::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tickets';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('number')->required()->maxLength(32),
            TextInput::make('subject')->required()->maxLength(255),
            Select::make('status')->options([
                'open' => 'Open',
                'pending' => 'Pending',
                'resolved' => 'Resolved',
                'closed' => 'Closed',
            ])->required(),
            TextInput::make('priority')->numeric()->default(3),
            TextInput::make('contact_email')->email(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('subject')->limit(40)->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('priority')->sortable(),
                TextColumn::make('created_at')->since(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTicket::route('/create'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }
}
