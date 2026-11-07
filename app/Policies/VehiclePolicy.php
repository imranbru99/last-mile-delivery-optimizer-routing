<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $this->ownsRecord($user, $vehicle);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $this->ownsRecord($user, $vehicle);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $this->ownsRecord($user, $vehicle);
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $this->ownsRecord($user, $vehicle);
    }

    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->isSuperAdmin();
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, Vehicle $vehicle): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $vehicle->business_id;
    }
}
