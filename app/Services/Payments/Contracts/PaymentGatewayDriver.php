<?php

declare(strict_types=1);

namespace App\Services\Payments\Contracts;

use App\Models\Invoice;
use App\Models\Payment;

interface PaymentGatewayDriver
{
    /**
     * @param  array<string, mixed>  $options
     */
    public function charge(Invoice $invoice, float $amount, array $options = []): Payment;
}
