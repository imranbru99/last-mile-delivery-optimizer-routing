<?php

declare(strict_types=1);

namespace App\Filament\Customer\Pages;

use App\Filament\Concerns\ModuleAware;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DeliveryPreferences extends Page implements HasForms
{
    use InteractsWithForms;
    use ModuleAware;

    public static string $module = 'delivery_preferences';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Preferences';

    protected static ?string $title = 'Delivery Preferences';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.customer.pages.delivery-preferences';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $meta = Auth::user()?->toArray()['preferences'] ?? [];
        $this->form->fill(is_array($meta) ? $meta : [
            'preferred_slot' => 'evening',
            'safe_place' => '',
            'notes' => '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('preferred_slot')
                    ->options([
                        'morning' => 'Morning (8–12)',
                        'afternoon' => 'Afternoon (12–17)',
                        'evening' => 'Evening (17–21)',
                    ])
                    ->required(),
                TextInput::make('safe_place')->label('Safe place / neighbor')->maxLength(255),
                Textarea::make('notes')->rows(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $user = Auth::user();

        if ($user !== null) {
            // Persist lightly on user without requiring a preferences column migration:
            // store in cache keyed by user for demo; production would use a preferences table.
            cache()->forever("customer.preferences.{$user->id}", $state);
        }

        Notification::make()->title('Preferences saved')->success()->send();
    }
}
