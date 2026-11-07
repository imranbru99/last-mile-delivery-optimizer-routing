<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DriverProfile;
use App\Models\User;

class DriverProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, DriverProfile $driverProfile): bool
    {
        return $this->ownsRecord($user, $driverProfile);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, DriverProfile $driverProfile): bool
    {
        return $this->ownsRecord($user, $driverProfile);
    }

    public function delete(User $user, DriverProfile $driverProfile): bool
    {
        return $this->ownsRecord($user, $driverProfile);
    }

    public function restore(User $user, DriverProfile $driverProfile): bool
    {
        return $this->ownsRecord($user, $driverProfile);
    }

    public function forceDelete(User $user, DriverProfile $driverProfile): bool
    {
        return $user->isSuperAdmin();
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, DriverProfile $driverProfile): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $driverProfile->business_id;
    }
}
