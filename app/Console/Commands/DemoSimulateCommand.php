<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\DeliveryRoute;
use App\Models\DriverLocation;
use App\Models\DriverProfile;
use App\Models\RouteStop;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DemoSimulateCommand extends Command
{
    protected $signature = 'demo:simulate
                            {--loops=1 : Number of simulation ticks}
                            {--sleep=0 : Seconds to sleep between ticks}';

    protected $description = 'Move demo drivers along in-progress routes for live map / tracking demos';

    public function handle(): int
    {
        $loops = max(1, (int) $this->option('loops'));
        $sleep = max(0, (int) $this->option('sleep'));

        for ($i = 0; $i < $loops; $i++) {
            $moved = $this->tick();
            $this->info("Tick ".($i + 1)."/{$loops}: updated {$moved} driver locations");

            if ($sleep > 0 && $i < $loops - 1) {
                sleep($sleep);
            }
        }

        return self::SUCCESS;
    }

    private function tick(): int
    {
        $routes = DeliveryRoute::query()
            ->whereIn('status', ['dispatched', 'in_progress'])
            ->whereNotNull('driver_id')
            ->with(['stops' => fn ($q) => $q->orderBy('sequence')])
            ->get();

        $count = 0;

        foreach ($routes as $route) {
            /** @var DeliveryRoute $route */
            $driver = DriverProfile::query()->find($route->driver_id);
            if (! $driver) {
                continue;
            }

            $nextStop = $route->stops
                ->first(fn (RouteStop $stop): bool => in_array($stop->status, ['pending', 'en_route', 'arrived'], true));

            if (! $nextStop || $nextStop->lat === null || $nextStop->lng === null) {
                continue;
            }

            $last = DriverLocation::query()
                ->where('driver_id', $driver->id)
                ->latest('recorded_at')
                ->first();

            $lat = (float) ($last?->lat ?? $route->stops->first()?->lat ?? $nextStop->lat);
            $lng = (float) ($last?->lng ?? $route->stops->first()?->lng ?? $nextStop->lng);

            $targetLat = (float) $nextStop->lat;
            $targetLng = (float) $nextStop->lng;

            $step = 0.18;
            $newLat = $lat + ($targetLat - $lat) * $step;
            $newLng = $lng + ($targetLng - $lng) * $step;

            DriverLocation::query()->create([
                'business_id' => $route->business_id,
                'driver_id' => $driver->id,
                'lat' => $newLat,
                'lng' => $newLng,
                'speed' => 28 + random_int(0, 12),
                'heading' => $this->bearing($lat, $lng, $targetLat, $targetLng),
                'accuracy' => 8.5,
                'recorded_at' => Carbon::now(),
            ]);

            if ($route->status === 'dispatched') {
                $route->update([
                    'status' => 'in_progress',
                    'started_at' => $route->started_at ?? now(),
                ]);
            }

            if ($this->haversineKm($newLat, $newLng, $targetLat, $targetLng) < 0.08) {
                $nextStop->update([
                    'status' => 'completed',
                    'ata_at' => now(),
                ]);
            } else {
                $nextStop->update(['status' => 'en_route']);
            }

            $count++;
        }

        return $count;
    }

    private function bearing(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $y = sin(deg2rad($lng2 - $lng1)) * cos(deg2rad($lat2));
        $x = cos(deg2rad($lat1)) * sin(deg2rad($lat2))
            - sin(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lng2 - $lng1));

        return fmod(rad2deg(atan2($y, $x)) + 360, 360);
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earth = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * $earth * asin(min(1, sqrt($a)));
    }
}
