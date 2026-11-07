<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'vehicle_id',
        'liters',
        'cost',
        'odometer',
        'filled_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'liters' => 'decimal:3',
            'cost' => 'decimal:2',
            'odometer' => 'integer',
            'filled_at' => 'datetime',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
