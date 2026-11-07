<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'category' => fake()->randomElement(['fuel', 'maintenance', 'tolls', 'office']),
            'description' => fake()->sentence(),
            'amount' => fake()->randomFloat(2, 20, 5000),
            'currency' => 'BDT',
            'incurred_at' => now(),
            'reference_type' => null,
            'reference_id' => null,
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => ['business_id' => $business->id]);
    }
}
