<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'number' => 'TKT-'.strtoupper(Str::random(6)),
            'subject' => fake()->sentence(4),
            'status' => 'open',
            'priority' => fake()->numberBetween(1, 5),
            'assigned_user_id' => null,
            'contact_email' => fake()->safeEmail(),
            'shipment_id' => null,
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => ['business_id' => $business->id]);
    }
}
