<?php

declare(strict_types=1);

namespace App\Modules\MerchantPortal;

use App\Modules\AbstractModule;

final class MerchantPortalModule extends AbstractModule
{
    public function key(): string
    {
        return 'merchant_portal';
    }
}
