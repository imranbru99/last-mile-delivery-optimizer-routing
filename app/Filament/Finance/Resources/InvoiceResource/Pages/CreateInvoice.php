<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources\InvoiceResource\Pages;

use App\Filament\Finance\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
