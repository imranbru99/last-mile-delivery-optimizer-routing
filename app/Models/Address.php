<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'label',
        'contact_name',
        'phone',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country_code',
        'lat',
        'lng',
        'formatted',
        'geocode_status',
        'geocode_raw',
        'what3words',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'geocode_raw' => 'array',
        ];
    }

    public function pickupShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'pickup_address_id');
    }

    public function deliveryShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'delivery_address_id');
    }
}
