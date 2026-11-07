<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MaintenanceLog;
use App\Models\User;

class MaintenanceLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, MaintenanceLog $maintenanceLog): bool
    {
        return $this->ownsRecord($user, $maintenanceLog);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, MaintenanceLog $maintenanceLog): bool
    {
        return $this->ownsRecord($user, $maintenanceLog);
    }

    public function delete(User $user, MaintenanceLog $maintenanceLog): bool
    {
        return $this->ownsRecord($user, $maintenanceLog);
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, MaintenanceLog $maintenanceLog): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $maintenanceLog->business_id;
    }
}
