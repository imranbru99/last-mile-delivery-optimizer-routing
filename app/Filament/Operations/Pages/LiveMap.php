<?php

declare(strict_types=1);

namespace App\Filament\Operations\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\DriverLocation;
use App\Models\DriverProfile;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class LiveMap extends Page
{
    use ModuleAware;

    public static string $module = 'live_tracking';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationLabel = 'Live Map';

    protected static ?string $title = 'Live Tracking Map';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.operations.pages.live-map';

    protected static ?string $pollingInterval = '10s';

    public function getMarkersProperty(): Collection
    {
        $driverIds = DriverProfile::query()->where('status', 'active')->pluck('id');

        return $driverIds->map(function ($driverId) {
            $loc = DriverLocation::query()
                ->where('driver_id', $driverId)
                ->latest('recorded_at')
                ->first();

            if (! $loc) {
                return null;
            }

            $driver = DriverProfile::query()->find($driverId);

            return [
                'driver' => $driver?->employee_code ?? (string) $driverId,
                'lat' => (float) $loc->lat,
                'lng' => (float) $loc->lng,
                'speed' => (float) ($loc->speed ?? 0),
                'recorded_at' => optional($loc->recorded_at)?->toIso8601String(),
            ];
        })->filter()->values();
    }
}
