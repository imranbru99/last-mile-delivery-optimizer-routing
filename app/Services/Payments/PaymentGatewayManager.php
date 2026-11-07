<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Payments\Contracts\PaymentGatewayDriver;
use App\Services\Payments\Drivers\BkashStubGatewayDriver;
use App\Services\Payments\Drivers\ManualGatewayDriver;
use App\Services\Payments\Drivers\StripeStubGatewayDriver;
use InvalidArgumentException;

final class PaymentGatewayManager
{
    /** @var array<string, PaymentGatewayDriver> */
    private array $drivers = [];

    public function __construct(
        ?ManualGatewayDriver $manual = null,
        ?StripeStubGatewayDriver $stripe = null,
        ?BkashStubGatewayDriver $bkash = null,
    ) {
        $this->drivers = [
            'manual' => $manual ?? new ManualGatewayDriver,
            'stripe_stub' => $stripe ?? new StripeStubGatewayDriver,
            'bkash_stub' => $bkash ?? new BkashStubGatewayDriver,
        ];
    }

    public function driver(string $name): PaymentGatewayDriver
    {
        if (! isset($this->drivers[$name])) {
            throw new InvalidArgumentException("Unknown payment gateway [{$name}].");
        }

        return $this->drivers[$name];
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function charge(Invoice $invoice, float $amount, string $gateway = 'manual', array $options = []): Payment
    {
        return $this->driver($gateway)->charge($invoice, $amount, $options);
    }

    /**
     * @return list<string>
     */
    public function available(): array
    {
        return array_keys($this->drivers);
    }
}
