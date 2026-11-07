<?php

declare(strict_types=1);

namespace App\Modules\SaasBilling;

use App\Modules\AbstractModule;

final class SaasBillingModule extends AbstractModule
{
    public function key(): string
    {
        return 'saas_billing';
    }
}
