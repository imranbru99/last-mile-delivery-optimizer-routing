<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources\ShipmentResource\Pages;

use App\Filament\Operations\Resources\ShipmentResource;
use App\Models\Address;
use App\Services\Addresses\AddressCleaningService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditShipment extends EditRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cleanDeliveryAddress')
                ->label('AI clean delivery address')
                ->icon(Heroicon::OutlinedSparkles)
                ->action(function (AddressCleaningService $cleaner): void {
                    $shipment = $this->getRecord();
                    $address = $shipment->deliveryAddress;
                    if (! $address instanceof Address) {
                        Notification::make()->title('No delivery address')->warning()->send();

                        return;
                    }

                    $cleaner->cleanAndPersist($address);
                    Notification::make()->title('Address cleaned')->success()->send();
                }),
            DeleteAction::make(),
        ];
    }
}
