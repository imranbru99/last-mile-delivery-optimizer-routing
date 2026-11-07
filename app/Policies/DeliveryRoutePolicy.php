<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DeliveryRoute;
use App\Models\User;

class DeliveryRoutePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, DeliveryRoute $deliveryRoute): bool
    {
        return $this->ownsRecord($user, $deliveryRoute);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, DeliveryRoute $deliveryRoute): bool
    {
        return $this->ownsRecord($user, $deliveryRoute);
    }

    public function delete(User $user, DeliveryRoute $deliveryRoute): bool
    {
        return $this->ownsRecord($user, $deliveryRoute);
    }

    public function restore(User $user, DeliveryRoute $deliveryRoute): bool
    {
        return $this->ownsRecord($user, $deliveryRoute);
    }

    public function forceDelete(User $user, DeliveryRoute $deliveryRoute): bool
    {
        return $user->isSuperAdmin();
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, DeliveryRoute $deliveryRoute): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $deliveryRoute->business_id;
    }
}
