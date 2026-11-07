<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources\ExpenseResource\Pages;

use App\Filament\Finance\Resources\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
