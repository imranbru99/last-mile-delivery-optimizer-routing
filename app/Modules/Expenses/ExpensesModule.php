<?php

declare(strict_types=1);

namespace App\Modules\Expenses;

use App\Modules\AbstractModule;

final class ExpensesModule extends AbstractModule
{
    public function key(): string
    {
        return 'expenses';
    }
}
