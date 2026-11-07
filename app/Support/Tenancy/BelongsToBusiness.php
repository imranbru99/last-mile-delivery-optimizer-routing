<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 */
trait BelongsToBusiness
{
    public static function bootBelongsToBusiness(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('business_id')) {
                $id = app(TenantContext::class)->id();
                if ($id) {
                    $model->setAttribute('business_id', $id);
                }
            }
        });

        static::addGlobalScope('business', function (Builder $builder): void {
            $context = app(TenantContext::class);
            $user = auth()->user();

            if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin() && ! $context->check()) {
                return;
            }

            if ($context->check()) {
                $builder->where(
                    $builder->getModel()->getTable().'.business_id',
                    $context->id()
                );
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
