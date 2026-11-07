<?php

declare(strict_types=1);

namespace App\Filament\Driver\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\DeliveryRoute;
use App\Models\DriverProfile;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class MyRoute extends Page
{
    use ModuleAware;

    public static string $module = 'driver_pwa';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'My Route';

    protected static ?string $title = 'Assigned stops';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.driver.pages.my-route';

    public function getRoutesProperty(): Collection
    {
        $user = auth()->user();
        $profile = DriverProfile::query()->where('user_id', $user?->id)->first();

        if (! $profile) {
            return collect();
        }

        return DeliveryRoute::query()
            ->where('driver_id', $profile->id)
            ->whereIn('status', ['dispatched', 'in_progress'])
            ->with(['stops' => fn ($q) => $q->orderBy('sequence')])
            ->orderByDesc('planned_date')
            ->get();
    }
}
