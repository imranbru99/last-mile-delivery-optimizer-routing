<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\MerchantProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MerchantProfile>
 */
class MerchantProfileFactory extends Factory
{
    protected $model = MerchantProfile::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'billing_email' => fake()->companyEmail(),
            'tax_id' => null,
            'meta' => [],
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => [
            'business_id' => $business->id,
            'user_id' => User::factory()->forBusiness($business),
        ]);
    }
}
