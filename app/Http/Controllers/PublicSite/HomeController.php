<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\InteractsWithModuleCatalog;
use App\Services\Performance\DrutogotiPageCache;
use Illuminate\View\View;

final class HomeController extends Controller
{
    use InteractsWithModuleCatalog;

    public function index(DrutogotiPageCache $cache): View
    {
        $viewData = $cache->remember('public.home', 120, function (): array {
            $highlightKeys = [
                'route_optimization',
                'dispatch_board',
                'live_tracking',
                'driver_pwa',
                'orders',
                'batching_waves',
                'ai_copilot',
                'customer_tracking',
            ];

            $highlights = $this->moduleRegistry()
                ->whereIn('key', $highlightKeys)
                ->values();

            return [
                'highlights' => $highlights,
                'categories' => $this->moduleCategories(),
                'moduleCount' => $this->moduleRegistry()->count(),
                'presetCount' => count($this->industryPresets()),
            ];
        });

        return view('public.home', $viewData);
    }
}
