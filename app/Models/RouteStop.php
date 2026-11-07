<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\RouteStopFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RouteStop extends Model
{
    /** @use HasFactory<RouteStopFactory> */
    use BelongsToBusiness;
    use HasFactory;

    protected $fillable = [
        'business_id',
        'route_id',
        'shipment_id',
        'sequence',
        'type',
        'status',
        'eta_at',
        'ata_at',
        'service_time_min',
        'lat',
        'lng',
        'address_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'eta_at' => 'datetime',
            'ata_at' => 'datetime',
            'service_time_min' => 'integer',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'address_snapshot' => 'array',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(DeliveryRoute::class, 'route_id');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function proofOfDeliveries(): HasMany
    {
        return $this->hasMany(ProofOfDelivery::class);
    }
}
