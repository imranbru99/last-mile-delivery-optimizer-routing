<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\RateCardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateCard extends Model
{
    /** @use HasFactory<RateCardFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'name',
        'code',
        'currency',
        'base_fee',
        'per_kg_fee',
        'weight_tiers',
        'zone_rules',
        'is_active',
        'valid_from',
        'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'base_fee' => 'decimal:2',
            'per_kg_fee' => 'decimal:4',
            'weight_tiers' => 'array',
            'zone_rules' => 'array',
            'is_active' => 'boolean',
            'valid_from' => 'date',
            'valid_until' => 'date',
        ];
    }
}
