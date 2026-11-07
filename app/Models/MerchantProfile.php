<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToBusiness;
use Database\Factories\MerchantProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantProfile extends Model
{
    /** @use HasFactory<MerchantProfileFactory> */
    use BelongsToBusiness;
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'company_name',
        'billing_email',
        'tax_id',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
