<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomFloat(2, 50, 2000),
            'currency' => 'BDT',
            'status' => 'completed',
            'gateway' => 'manual',
            'reference' => fake()->uuid(),
            'meta' => [],
            'paid_at' => now(),
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => ['business_id' => $business->id]);
    }
}
