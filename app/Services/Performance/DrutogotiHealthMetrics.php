<?php

declare(strict_types=1);

namespace App\Services\Performance;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class DrutogotiHealthMetrics
{
    /**
     * @return list<array{label: string, value: int|string, hint?: string}>
     */
    public function snapshot(): array
    {
        if (! Schema::hasTable('drutogoti_requests')) {
            return [
                ['label' => 'Drutogoti', 'value' => 'N/A', 'hint' => 'Profiling tables not migrated'],
            ];
        }

        $requests = (int) DB::table('drutogoti_requests')->count();
        $queries = Schema::hasTable('drutogoti_query_logs')
            ? (int) DB::table('drutogoti_query_logs')->count()
            : 0;
        $suggestions = Schema::hasTable('drutogoti_suggestions')
            ? (int) DB::table('drutogoti_suggestions')->where('is_resolved', false)->where('is_dismissed', false)->count()
            : 0;
        $cacheEvents = Schema::hasTable('drutogoti_cache_events')
            ? (int) DB::table('drutogoti_cache_events')->count()
            : 0;

        return [
            ['label' => 'Captured requests', 'value' => $requests],
            ['label' => 'Query logs', 'value' => $queries],
            ['label' => 'Open suggestions', 'value' => $suggestions],
            ['label' => 'Cache events', 'value' => $cacheEvents, 'hint' => 'imrandevbd/drutogoti'],
        ];
    }
}
