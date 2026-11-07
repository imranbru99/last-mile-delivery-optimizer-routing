<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use BelongsToBusiness;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'invoice_number',
        'shipment_id',
        'merchant_user_id',
        'status',
        'subtotal',
        'tax',
        'total',
        'currency',
        'line_items',
        'notes',
        'pdf_path',
        'issued_at',
        'due_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'line_items' => 'array',
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function merchantUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
