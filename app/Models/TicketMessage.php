<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\TicketMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    /** @use HasFactory<TicketMessageFactory> */
    use BelongsToBusiness;
    use HasFactory;

    protected $fillable = [
        'business_id',
        'ticket_id',
        'user_id',
        'body',
        'is_internal',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
