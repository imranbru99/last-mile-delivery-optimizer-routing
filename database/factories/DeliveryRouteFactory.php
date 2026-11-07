<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\DeliveryRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliveryRoute>
 */
class DeliveryRouteFactory extends Factory
{
    protected $model = DeliveryRoute::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => 'Route '.fake()->dayOfWeek(),
            'code' => strtoupper(fake()->unique()->bothify('RT-####')),
            'status' => 'draft',
            'driver_id' => null,
            'vehicle_id' => null,
            'hub_id' => null,
            'planned_date' => now()->toDateString(),
            'started_at' => null,
            'completed_at' => null,
            'total_distance_km' => null,
            'total_duration_min' => null,
            'total_stops' => 0,
            'optimization_meta' => [],
        ];
    }
}
