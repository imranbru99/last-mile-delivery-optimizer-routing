<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'icon',
        'category',
        'dependencies',
        'version',
        'default_enabled',
        'always_on',
        'pricing_tier',
        'settings_schema',
        'what_you_lose',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'dependencies' => 'array',
            'settings_schema' => 'array',
            'default_enabled' => 'boolean',
            'always_on' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
