<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Zone;

class ZonePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, Zone $zone): bool
    {
        return $this->ownsRecord($user, $zone);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, Zone $zone): bool
    {
        return $this->ownsRecord($user, $zone);
    }

    public function delete(User $user, Zone $zone): bool
    {
        return $this->ownsRecord($user, $zone);
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, Zone $zone): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $zone->business_id;
    }
}
