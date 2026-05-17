<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformPlan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'saas_packages';

    protected $fillable = [
        'name',
        'description',
        'billing_interval',
        'interval_count',
        'trial_days',
        'price',
        'currency',
        'location_limit',
        'user_limit',
        'product_limit',
        'invoice_limit',
        'features',
        'custom_permissions',
        'is_active',
        'is_private',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'custom_permissions' => 'array',
        'is_active' => 'boolean',
        'is_private' => 'boolean',
        'is_popular' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
