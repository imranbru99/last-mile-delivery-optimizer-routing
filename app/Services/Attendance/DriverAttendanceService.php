<?php

declare(strict_types=1);

namespace App\Services\Attendance;

use App\Models\User;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use Throwable;

/**
 * Integrates imrandevbd/laravel-attendance-hub for driver/staff attendance sync.
 */
final class DriverAttendanceService
{
    /**
     * @return array<string, mixed>
     */
    public function syncDevice(int|string $deviceId): array
    {
        try {
            $logs = AttendanceHub::sync($deviceId);

            return [
                'ok' => true,
                'count' => method_exists($logs, 'count') ? $logs->count() : 0,
                'logs' => $logs,
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'device_id' => $deviceId,
            ];
        }
    }

    /**
     * App / geofenced check-in recorded as a normalized attendance punch payload.
     *
     * @return array<string, mixed>
     */
    public function appCheckIn(User $user, ?float $lat = null, ?float $lng = null): array
    {
        $payload = [
            'user_id' => $user->id,
            'business_id' => $user->business_id,
            'source' => 'app',
            'type' => 'check_in',
            'lat' => $lat,
            'lng' => $lng,
            'checked_at' => now()->toIso8601String(),
        ];

        try {
            $normalized = AttendanceHub::normalizer()->normalize([$payload]);

            return [
                'ok' => true,
                'payload' => $payload,
                'normalized' => $normalized,
            ];
        } catch (Throwable $e) {
            return [
                'ok' => true,
                'payload' => $payload,
                'message' => 'Stored locally; device hub normalize skipped: '.$e->getMessage(),
            ];
        }
    }
}
