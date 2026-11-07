<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Hub;
use App\Models\User;

class HubPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function view(User $user, Hub $hub): bool
    {
        return $this->ownsRecord($user, $hub);
    }

    public function create(User $user): bool
    {
        return $this->belongsToTenant($user);
    }

    public function update(User $user, Hub $hub): bool
    {
        return $this->ownsRecord($user, $hub);
    }

    public function delete(User $user, Hub $hub): bool
    {
        return $this->ownsRecord($user, $hub);
    }

    public function restore(User $user, Hub $hub): bool
    {
        return $this->ownsRecord($user, $hub);
    }

    public function forceDelete(User $user, Hub $hub): bool
    {
        return $user->isSuperAdmin();
    }

    private function belongsToTenant(User $user): bool
    {
        return $user->isSuperAdmin() || $user->business_id !== null;
    }

    private function ownsRecord(User $user, Hub $hub): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->business_id !== null && (int) $user->business_id === (int) $hub->business_id;
    }
}
