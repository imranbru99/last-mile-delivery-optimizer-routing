<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);

        return [
            'business_id' => Business::factory(),
            'invoice_number' => 'INV-'.strtoupper(Str::random(8)),
            'shipment_id' => null,
            'merchant_user_id' => null,
            'status' => 'draft',
            'subtotal' => $subtotal,
            'tax' => round($subtotal * 0.05, 2),
            'total' => round($subtotal * 1.05, 2),
            'currency' => 'BDT',
            'line_items' => [],
            'notes' => null,
            'pdf_path' => null,
            'issued_at' => null,
            'due_at' => now()->addDays(14),
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => ['business_id' => $business->id]);
    }
}
