<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'name',
        'plate_number',
        'type',
        'capacity_weight_kg',
        'capacity_volume_m3',
        'capacity_stops',
        'status',
        'hub_id',
        'documents',
    ];

    protected function casts(): array
    {
        return [
            'capacity_weight_kg' => 'decimal:3',
            'capacity_volume_m3' => 'decimal:3',
            'capacity_stops' => 'integer',
            'documents' => 'array',
        ];
    }

    public function hub(): BelongsTo
    {
        return $this->belongsTo(Hub::class);
    }

    public function driverProfiles(): HasMany
    {
        return $this->hasMany(DriverProfile::class);
    }

    public function deliveryRoutes(): HasMany
    {
        return $this->hasMany(DeliveryRoute::class, 'vehicle_id');
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}
