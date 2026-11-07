<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\DriverProfile;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverProfile>
 */
class DriverProfileFactory extends Factory
{
    protected $model = DriverProfile::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'user_id' => User::factory(),
            'employee_code' => strtoupper(fake()->unique()->bothify('DRV-####')),
            'license_number' => fake()->bothify('??-######'),
            'license_expires_at' => now()->addYears(2),
            'status' => 'active',
            'rating' => fake()->randomFloat(2, 3.5, 5),
            'skills' => ['last_mile'],
            'hub_id' => Hub::factory(),
            'vehicle_id' => null,
        ];
    }

    public function forBusinessHub(Business $business, Hub $hub): static
    {
        return $this->state(fn (): array => [
            'business_id' => $business->id,
            'hub_id' => $hub->id,
            'user_id' => User::factory()->state(['business_id' => $business->id]),
        ]);
    }
}
