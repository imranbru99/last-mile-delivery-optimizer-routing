<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Business;
use App\Models\DeliveryRoute;
use App\Models\DriverLocation;
use App\Models\DriverProfile;
use App\Models\Hub;
use App\Models\RouteStop;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Support\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OperationalDemoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Business::query()->get() as $business) {
            $this->seedBusinessOps($business);
        }
    }

    private function seedBusinessOps(Business $business): void
    {
        $hubs = collect([
            ['name' => $business->city.' Central Hub', 'code' => 'HUB-01', 'lat' => 23.8103, 'lng' => 90.4125],
            ['name' => $business->city.' North Hub', 'code' => 'HUB-02', 'lat' => 23.8750, 'lng' => 90.3900],
        ])->map(fn (array $h) => Hub::query()->updateOrCreate(
            ['business_id' => $business->id, 'code' => $h['code']],
            [
                'name' => $h['name'],
                'type' => 'hub',
                'address' => $business->city.', Bangladesh',
                'lat' => $h['lat'],
                'lng' => $h['lng'],
                'capacity_json' => ['weight_kg' => 5000, 'volume_m3' => 120],
                'is_active' => true,
            ]
        ));

        $zones = collect(range(1, 4))->map(fn (int $i) => Zone::query()->updateOrCreate(
            ['business_id' => $business->id, 'code' => 'Z'.$i],
            [
                'name' => 'Zone '.$i,
                'color' => ['#0F766E', '#0369A1', '#B45309', '#BE123C'][$i - 1],
                'polygon_json' => [
                    ['lat' => 23.80 + $i * 0.01, 'lng' => 90.35],
                    ['lat' => 23.80 + $i * 0.01, 'lng' => 90.45],
                    ['lat' => 23.75 + $i * 0.01, 'lng' => 90.45],
                    ['lat' => 23.75 + $i * 0.01, 'lng' => 90.35],
                ],
                'hub_id' => $hubs->random()->id,
                'pricing_multiplier' => 1 + ($i * 0.05),
                'is_active' => true,
            ]
        ));

        $vehicles = collect(range(1, 8))->map(fn (int $i) => Vehicle::query()->updateOrCreate(
            ['business_id' => $business->id, 'plate_number' => strtoupper(substr($business->slug, 0, 3)).'-'.$i.str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
            [
                'name' => 'Van '.$i,
                'type' => $i % 3 === 0 ? 'bike' : 'van',
                'capacity_weight_kg' => $i % 3 === 0 ? 40 : 800,
                'capacity_volume_m3' => $i % 3 === 0 ? 0.2 : 8,
                'capacity_stops' => $i % 3 === 0 ? 15 : 40,
                'status' => 'active',
                'hub_id' => $hubs->random()->id,
                'documents' => ['registration' => 'demo'],
            ]
        ));

        $driverUsers = User::query()
            ->where('business_id', $business->id)
            ->where('role_hint', UserRole::Driver->value)
            ->get();

        // Ensure enough driver users
        while ($driverUsers->count() < 10) {
            $n = $driverUsers->count() + 1;
            $user = User::query()->updateOrCreate(
                ['email' => "driver{$n}@{$business->slug}.test"],
                [
                    'business_id' => $business->id,
                    'name' => "Driver {$n}",
                    'password' => bcrypt('password'),
                    'role_hint' => UserRole::Driver->value,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([UserRole::Driver->value]);
            $driverUsers->push($user);
        }

        $drivers = $driverUsers->values()->map(function (User $user, int $i) use ($business, $hubs, $vehicles) {
            return DriverProfile::query()->updateOrCreate(
                ['business_id' => $business->id, 'user_id' => $user->id],
                [
                    'employee_code' => 'DRV-'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                    'license_number' => 'LIC-'.Str::upper(Str::random(6)),
                    'license_expires_at' => now()->addYear(),
                    'status' => 'active',
                    'rating' => 4.2 + (mt_rand(0, 8) / 10),
                    'skills' => ['cod', 'fragile'],
                    'hub_id' => $hubs->random()->id,
                    'vehicle_id' => $vehicles[$i % $vehicles->count()]->id,
                ]
            );
        });

        $statuses = ['draft', 'created', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned'];
        $shipmentCount = match ($business->business_type) {
            'courier' => 260,
            'grocery' => 180,
            default => 100,
        };

        for ($i = 1; $i <= $shipmentCount; $i++) {
            $pickup = Address::factory()->create([
                'business_id' => $business->id,
                'city' => $business->city,
                'lat' => 23.75 + (mt_rand(-50, 50) / 1000),
                'lng' => 90.35 + (mt_rand(-50, 50) / 1000),
            ]);
            $delivery = Address::factory()->create([
                'business_id' => $business->id,
                'city' => $business->city,
                'lat' => 23.75 + (mt_rand(-80, 80) / 1000),
                'lng' => 90.35 + (mt_rand(-80, 80) / 1000),
            ]);

            Shipment::query()->updateOrCreate(
                ['tracking_code' => strtoupper(substr($business->slug, 0, 3)).str_pad((string) $i, 8, '0', STR_PAD_LEFT)],
                [
                    'business_id' => $business->id,
                    'service_level' => ['standard', 'express', 'same_day'][$i % 3],
                    'priority' => ($i % 5) + 1,
                    'status' => $statuses[$i % count($statuses)],
                    'pickup_address_id' => $pickup->id,
                    'delivery_address_id' => $delivery->id,
                    'hub_id' => $hubs->random()->id,
                    'zone_id' => $zones->random()->id,
                    'weight_kg' => mt_rand(5, 250) / 10,
                    'volume_m3' => mt_rand(1, 50) / 100,
                    'pieces' => mt_rand(1, 4),
                    'time_window_start' => now()->startOfDay()->addHours(9),
                    'time_window_end' => now()->startOfDay()->addHours(18),
                    'cod_amount' => $i % 3 === 0 ? mt_rand(200, 2500) : 0,
                    'currency' => 'BDT',
                    'barcode' => 'BC'.str_pad((string) $i, 10, '0', STR_PAD_LEFT),
                ]
            );
        }

        $routeStatuses = ['planned', 'dispatched', 'in_progress', 'completed'];
        for ($r = 1; $r <= 15; $r++) {
            $driver = $drivers[$r % $drivers->count()];
            $route = DeliveryRoute::query()->updateOrCreate(
                ['business_id' => $business->id, 'code' => 'RT-'.str_pad((string) $r, 4, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Route '.$r,
                    'status' => $routeStatuses[$r % count($routeStatuses)],
                    'driver_id' => $driver->id,
                    'vehicle_id' => $driver->vehicle_id,
                    'hub_id' => $driver->hub_id,
                    'planned_date' => now()->toDateString(),
                    'total_distance_km' => mt_rand(20, 90),
                    'total_duration_min' => mt_rand(90, 360),
                    'total_stops' => 8,
                    'optimization_meta' => ['engine' => 'native', 'saved_km' => mt_rand(3, 18)],
                    'started_at' => in_array($routeStatuses[$r % count($routeStatuses)], ['in_progress', 'completed'], true) ? now()->subHours(2) : null,
                ]
            );

            $shipments = Shipment::query()
                ->where('business_id', $business->id)
                ->inRandomOrder()
                ->limit(8)
                ->get();

            foreach ($shipments->values() as $seq => $shipment) {
                RouteStop::query()->updateOrCreate(
                    [
                        'business_id' => $business->id,
                        'route_id' => $route->id,
                        'shipment_id' => $shipment->id,
                    ],
                    [
                        'sequence' => $seq + 1,
                        'type' => 'delivery',
                        'status' => $seq < 2 ? 'completed' : 'pending',
                        'eta_at' => now()->addMinutes(($seq + 1) * 25),
                        'service_time_min' => 5,
                        'lat' => $shipment->deliveryAddress?->lat ?? 23.78,
                        'lng' => $shipment->deliveryAddress?->lng ?? 90.40,
                        'address_snapshot' => ['tracking' => $shipment->tracking_code],
                    ]
                );
            }

            if (in_array($route->status, ['dispatched', 'in_progress'], true)) {
                DriverLocation::query()->create([
                    'business_id' => $business->id,
                    'driver_id' => $driver->id,
                    'lat' => 23.80 + (mt_rand(-20, 20) / 1000),
                    'lng' => 90.40 + (mt_rand(-20, 20) / 1000),
                    'speed' => 30,
                    'heading' => mt_rand(0, 359),
                    'accuracy' => 10,
                    'recorded_at' => now(),
                ]);
            }
        }
    }
}
