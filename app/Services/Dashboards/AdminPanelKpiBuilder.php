<?php

declare(strict_types=1);

namespace App\Services\Dashboards;

use App\Models\Business;
use App\Models\DeliveryRoute;
use App\Models\Shipment;
use ImranDevBD\LaravelAdminPanel\Facades\AdminPanel;
use Throwable;

/**
 * KPI card builder using imrandevbd/laravel-admin-panel panel registry.
 */
final class AdminPanelKpiBuilder
{
    /**
     * @return list<array{label: string, value: int|float|string, hint?: string}>
     */
    public function forBusiness(Business $business): array
    {
        $base = [
            [
                'label' => 'Active modules',
                'value' => $business->businessModules()->where('is_enabled', true)->count(),
            ],
            [
                'label' => 'Team members',
                'value' => $business->users()->count(),
            ],
            [
                'label' => 'Shipments (7d)',
                'value' => Shipment::query()
                    ->where('business_id', $business->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
            ],
            [
                'label' => 'Routes in progress',
                'value' => DeliveryRoute::query()
                    ->where('business_id', $business->id)
                    ->whereIn('status', ['dispatched', 'in_progress'])
                    ->count(),
            ],
            [
                'label' => 'Out for delivery',
                'value' => Shipment::query()
                    ->where('business_id', $business->id)
                    ->whereIn('status', ['out_for_delivery', 'in_transit', 'assigned'])
                    ->count(),
            ],
            [
                'label' => 'Plan',
                'value' => (string) $business->plan,
            ],
        ];

        try {
            $version = AdminPanel::version();
            $base[] = [
                'label' => 'Admin Panel',
                'value' => $version,
                'hint' => 'imrandevbd/laravel-admin-panel',
            ];
        } catch (Throwable) {
            // Package optional during early boot / tests.
        }

        return $base;
    }
}
