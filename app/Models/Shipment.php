<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\ShipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    /** @use HasFactory<ShipmentFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'tracking_code',
        'merchant_user_id',
        'service_level',
        'priority',
        'status',
        'pickup_address_id',
        'delivery_address_id',
        'hub_id',
        'zone_id',
        'weight_kg',
        'volume_m3',
        'pieces',
        'time_window_start',
        'time_window_end',
        'cod_amount',
        'currency',
        'special_handling',
        'barcode',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
            'weight_kg' => 'decimal:3',
            'volume_m3' => 'decimal:3',
            'pieces' => 'integer',
            'time_window_start' => 'datetime',
            'time_window_end' => 'datetime',
            'cod_amount' => 'decimal:2',
            'special_handling' => 'array',
        ];
    }

    public function merchantUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_user_id');
    }

    public function pickupAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'pickup_address_id');
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    public function hub(): BelongsTo
    {
        return $this->belongsTo(Hub::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class);
    }

    public function routeStops(): HasMany
    {
        return $this->hasMany(RouteStop::class);
    }

    public function proofOfDeliveries(): HasMany
    {
        return $this->hasMany(ProofOfDelivery::class);
    }
}
