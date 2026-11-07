<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\ProofOfDeliveryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProofOfDelivery extends Model
{
    /** @use HasFactory<ProofOfDeliveryFactory> */
    use BelongsToBusiness;
    use HasFactory;

    protected $table = 'proof_of_deliveries';

    protected $fillable = [
        'business_id',
        'shipment_id',
        'route_stop_id',
        'method',
        'recipient_name',
        'signature_path',
        'photo_path',
        'otp_verified',
        'lat',
        'lng',
        'captured_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'otp_verified' => 'boolean',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'captured_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function routeStop(): BelongsTo
    {
        return $this->belongsTo(RouteStop::class);
    }
}
