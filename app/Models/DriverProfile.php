<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\DriverProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverProfile extends Model
{
    /** @use HasFactory<DriverProfileFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'drivers_profiles';

    protected $fillable = [
        'business_id',
        'user_id',
        'employee_code',
        'license_number',
        'license_expires_at',
        'status',
        'rating',
        'skills',
        'hub_id',
        'vehicle_id',
    ];

    protected function casts(): array
    {
        return [
            'license_expires_at' => 'date',
            'rating' => 'decimal:2',
            'skills' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hub(): BelongsTo
    {
        return $this->belongsTo(Hub::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function deliveryRoutes(): HasMany
    {
        return $this->hasMany(DeliveryRoute::class, 'driver_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(DriverLocation::class, 'driver_id');
    }
}
