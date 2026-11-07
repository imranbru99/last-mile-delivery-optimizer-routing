<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\DeliveryRouteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryRoute extends Model
{
    /** @use HasFactory<DeliveryRouteFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'routes';

    protected $fillable = [
        'business_id',
        'name',
        'code',
        'status',
        'driver_id',
        'vehicle_id',
        'hub_id',
        'planned_date',
        'started_at',
        'completed_at',
        'total_distance_km',
        'total_duration_min',
        'total_stops',
        'optimization_meta',
    ];

    protected function casts(): array
    {
        return [
            'planned_date' => 'date',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_distance_km' => 'decimal:3',
            'total_duration_min' => 'integer',
            'total_stops' => 'integer',
            'optimization_meta' => 'array',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverProfile::class, 'driver_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function hub(): BelongsTo
    {
        return $this->belongsTo(Hub::class);
    }

    public function stops(): HasMany
    {
        return $this->hasMany(RouteStop::class, 'route_id')->orderBy('sequence');
    }
}
