<?php

declare(strict_types=1);

namespace App\Services\Payments\Drivers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Payments\Contracts\PaymentGatewayDriver;
use Illuminate\Support\Str;

final class ManualGatewayDriver implements PaymentGatewayDriver
{
    public function charge(Invoice $invoice, float $amount, array $options = []): Payment
    {
        return Payment::query()->create([
            'business_id' => $invoice->business_id,
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'currency' => $invoice->currency,
            'status' => 'completed',
            'gateway' => 'manual',
            'reference' => $options['reference'] ?? 'MAN-'.Str::upper(Str::random(10)),
            'meta' => $options,
            'paid_at' => now(),
        ]);
    }
}
