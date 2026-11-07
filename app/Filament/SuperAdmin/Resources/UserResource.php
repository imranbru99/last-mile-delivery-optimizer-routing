<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\Concerns\SuperAdminOnly;
use App\Filament\SuperAdmin\Resources\UserResource\Pages\CreateUser;
use App\Filament\SuperAdmin\Resources\UserResource\Pages\EditUser;
use App\Filament\SuperAdmin\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use App\Support\Enums\UserRole;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use SuperAdminOnly;

    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'User';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        $roleOptions = collect(UserRole::cases())
            ->mapWithKeys(fn (UserRole $role): array => [$role->value => $role->label()])
            ->all();

        return $schema->components([
            Section::make('Account')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Select::make('business_id')
                    ->label('Business / tenant')
                    ->relationship('business', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Leave empty for platform super admins.'),
                Select::make('role_hint')
                    ->label('Role')
                    ->options($roleOptions)
                    ->required()
                    ->searchable(),
                Toggle::make('is_active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        $roleOptions = collect(UserRole::cases())
            ->mapWithKeys(fn (UserRole $role): array => [$role->value => $role->label()])
            ->all();

        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('business.name')
                    ->label('Business')
                    ->placeholder('Platform')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('role_hint')
                    ->label('Role')
                    ->formatStateUsing(fn (?string $state): string => $roleOptions[$state] ?? ($state ?? '—'))
                    ->badge(),
                IconColumn::make('is_active')->boolean()->sortable(),
                TextColumn::make('last_login_at')->since()->toggleable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('business_id')
                    ->label('Business')
                    ->relationship('business', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('role_hint')
                    ->label('Role')
                    ->options($roleOptions),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
