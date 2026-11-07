<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FuelLog;
use App\Models\User;

class FuelLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, FuelLog $fuelLog): bool
    {
        return $this->ownsRecord($user, $fuelLog);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, FuelLog $fuelLog): bool
    {
        return $this->ownsRecord($user, $fuelLog);
    }

    public function delete(User $user, FuelLog $fuelLog): bool
    {
        return $this->ownsRecord($user, $fuelLog);
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, FuelLog $fuelLog): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $fuelLog->business_id;
    }
}
