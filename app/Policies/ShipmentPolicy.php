<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, Shipment $shipment): bool
    {
        return $this->ownsRecord($user, $shipment);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, Shipment $shipment): bool
    {
        return $this->ownsRecord($user, $shipment);
    }

    public function delete(User $user, Shipment $shipment): bool
    {
        return $this->ownsRecord($user, $shipment);
    }

    public function restore(User $user, Shipment $shipment): bool
    {
        return $this->ownsRecord($user, $shipment);
    }

    public function forceDelete(User $user, Shipment $shipment): bool
    {
        return $user->isSuperAdmin();
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, Shipment $shipment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $shipment->business_id;
    }
}
