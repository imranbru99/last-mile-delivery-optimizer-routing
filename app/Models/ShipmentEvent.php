<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\ShipmentEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentEvent extends Model
{
    /** @use HasFactory<ShipmentEventFactory> */
    use BelongsToBusiness;
    use HasFactory;

    protected $fillable = [
        'business_id',
        'shipment_id',
        'type',
        'payload',
        'user_id',
        'happened_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'happened_at' => 'datetime',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
