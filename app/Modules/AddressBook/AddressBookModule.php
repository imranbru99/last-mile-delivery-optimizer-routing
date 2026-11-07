<?php

declare(strict_types=1);

namespace App\Modules\AddressBook;

use App\Modules\AbstractModule;

final class AddressBookModule extends AbstractModule
{
    public function key(): string
    {
        return 'address_book';
    }
}
