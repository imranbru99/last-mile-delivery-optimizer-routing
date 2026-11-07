<?php

declare(strict_types=1);

namespace App\Services\Routing\Contracts;

use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationResult;

interface RoutingEngine
{
    public function optimize(OptimizationRequest $request): OptimizationResult;
}
