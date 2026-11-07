<?php

declare(strict_types=1);

namespace App\Services\Performance;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Thin caching helper used on heavy public/route pages.
 * Works alongside imrandevbd/drutogoti request profiling.
 */
final class DrutogotiPageCache
{
    public function remember(string $key, int $ttl, Closure $callback): mixed
    {
        return Cache::remember('lmdo.page.'.$key, $ttl, $callback);
    }

    public function forget(string $key): void
    {
        Cache::forget('lmdo.page.'.$key);
    }
}
