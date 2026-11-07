<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Models\Business;
use App\Models\User;

final class TenantContext
{
    private ?Business $business = null;

    public function set(?Business $business): void
    {
        $this->business = $business;
    }

    public function business(): ?Business
    {
        return $this->business;
    }

    public function id(): ?int
    {
        return $this->business?->id;
    }

    public function setFromUser(?User $user): void
    {
        if ($user === null) {
            $this->business = null;

            return;
        }

        if ($user->isSuperAdmin()) {
            if ($user->impersonated_business_id) {
                $this->business = Business::query()->find($user->impersonated_business_id);
            }

            return;
        }

        $this->business = $user->business;
    }

    public function check(): bool
    {
        return $this->business !== null;
    }
}
