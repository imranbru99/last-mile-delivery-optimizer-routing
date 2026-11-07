<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources\ShipmentResource\Pages;

use App\Filament\Operations\Resources\ShipmentResource;
use App\Models\Address;
use App\Services\Addresses\AddressCleaningService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cleanDeliveryAddress')
                ->label('AI clean delivery address')
                ->icon(Heroicon::OutlinedSparkles)
                ->visible(fn (): bool => (bool) $this->data['delivery_address_id'] ?? false)
                ->action(function (AddressCleaningService $cleaner): void {
                    $id = $this->data['delivery_address_id'] ?? null;
                    if (! $id) {
                        return;
                    }

                    $address = Address::query()->find($id);
                    if (! $address) {
                        Notification::make()->title('Address not found')->danger()->send();

                        return;
                    }

                    $cleaner->cleanAndPersist($address);
                    Notification::make()
                        ->title('Delivery address cleaned')
                        ->body($address->formatted)
                        ->success()
                        ->send();
                }),
        ];
    }
}
