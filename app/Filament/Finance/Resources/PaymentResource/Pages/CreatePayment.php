<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources\PaymentResource\Pages;

use App\Filament\Finance\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
