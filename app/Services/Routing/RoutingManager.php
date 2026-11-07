<?php

declare(strict_types=1);

namespace App\Services\Routing;

use App\Services\Routing\Contracts\RoutingEngine;
use InvalidArgumentException;

final class RoutingManager
{
    public function driver(): string
    {
        return (string) config('routing.default', 'native');
    }

    public function engine(?string $driver = null): RoutingEngine
    {
        $driver ??= $this->driver();
        $class = config("routing.drivers.{$driver}");

        if (! is_string($class) || $class === '' || ! class_exists($class)) {
            throw new InvalidArgumentException("Routing engine [{$driver}] is not configured.");
        }

        $engine = app($class);

        if (! $engine instanceof RoutingEngine) {
            throw new InvalidArgumentException("Routing engine [{$driver}] must implement ".RoutingEngine::class);
        }

        return $engine;
    }
}
