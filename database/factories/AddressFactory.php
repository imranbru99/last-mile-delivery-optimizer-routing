<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'label' => fake()->randomElement(['Home', 'Office', 'Warehouse']),
            'contact_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'line1' => fake()->streetAddress(),
            'line2' => null,
            'city' => fake()->randomElement(['Dhaka', 'Chattogram', 'Sylhet']),
            'state' => 'Dhaka Division',
            'postal_code' => fake()->postcode(),
            'country_code' => 'BD',
            'lat' => fake()->latitude(23.7, 23.9),
            'lng' => fake()->longitude(90.3, 90.5),
            'formatted' => null,
            'geocode_status' => 'ok',
            'geocode_raw' => [],
            'what3words' => null,
        ];
    }
}
