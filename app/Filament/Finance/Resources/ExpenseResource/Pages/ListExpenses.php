<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources\ExpenseResource\Pages;

use App\Filament\Finance\Resources\ExpenseResource;
use Filament\Resources\Pages\ListRecords;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;
}
