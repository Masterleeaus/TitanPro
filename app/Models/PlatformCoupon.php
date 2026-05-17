<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformCoupon extends Model
{
    use HasFactory;

    protected $table = 'saas_coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'applies_to_plans',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'applies_to_plans' => 'array',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];
}
