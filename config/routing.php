<?php

declare(strict_types=1);

return [
    'default' => env('ROUTING_ENGINE', 'native'),

    'drivers' => [
        'native' => App\Services\Routing\NativeHeuristicSolver::class,
        'osrm' => App\Services\Routing\Drivers\OsrmRoutingEngine::class,
        'graphhopper' => App\Services\Routing\Drivers\GraphHopperRoutingEngine::class,
        'google' => App\Services\Routing\Drivers\GoogleRoutesEngine::class,
    ],

    'osrm_url' => env('OSRM_URL', 'https://router.project-osrm.org'),
    'graphhopper_url' => env('GRAPHHOPPER_URL', 'https://graphhopper.com/api/1'),
    'graphhopper_key' => env('GRAPHHOPPER_API_KEY', ''),
    'google_key' => env('GOOGLE_MAPS_API_KEY', ''),

    'speed_kmh' => (float) env('ROUTING_AVG_SPEED_KMH', 25),

    'service_time_min' => (int) env('ROUTING_DEFAULT_SERVICE_MIN', 5),
];
