<?php

declare(strict_types=1);

namespace App\Filament\Driver\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\ProofOfDelivery;
use App\Models\Shipment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class CapturePod extends Page implements HasForms
{
    use InteractsWithForms;
    use ModuleAware;

    public static string $module = 'pod';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationLabel = 'Capture POD';

    protected static ?string $title = 'Proof of delivery';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.driver.pages.capture-pod';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'method' => 'signature',
            'otp_verified' => false,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('shipment_id')
                    ->label('Shipment')
                    ->options(fn (): array => Shipment::query()
                        ->orderByDesc('id')
                        ->limit(100)
                        ->pluck('tracking_code', 'id')
                        ->all())
                    ->searchable()
                    ->required(),
                TextInput::make('recipient_name')->maxLength(255),
                Select::make('method')
                    ->options([
                        'signature' => 'Signature',
                        'photo' => 'Photo',
                        'otp' => 'OTP',
                        'manual' => 'Manual',
                    ])
                    ->required(),
                TextInput::make('lat')->numeric(),
                TextInput::make('lng')->numeric(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        ProofOfDelivery::query()->create([
            'shipment_id' => $state['shipment_id'],
            'method' => $state['method'],
            'recipient_name' => $state['recipient_name'] ?? null,
            'lat' => $state['lat'] ?? null,
            'lng' => $state['lng'] ?? null,
            'captured_at' => now(),
            'otp_verified' => ($state['method'] ?? '') === 'otp',
        ]);

        Notification::make()
            ->title('POD saved')
            ->success()
            ->send();

        $this->form->fill(['method' => 'signature']);
    }
}
