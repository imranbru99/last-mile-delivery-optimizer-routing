<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketMessage>
 */
class TicketMessageFactory extends Factory
{
    protected $model = TicketMessage::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'ticket_id' => Ticket::factory(),
            'user_id' => null,
            'body' => fake()->paragraph(),
            'is_internal' => false,
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => [
            'business_id' => $business->id,
            'ticket_id' => Ticket::factory()->forBusiness($business),
        ]);
    }
}
