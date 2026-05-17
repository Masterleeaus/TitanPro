<?php

namespace Modules\ZeroFussPortal\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $table = 'zerofuss_loyalty_points';

    protected $fillable = [
        'company_id',
        'customer_id',
        'points',
        'direction',
        'reason',
        'source_type',
        'source_id',
        'metadata',
        'awarded_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'awarded_at' => 'datetime',
    ];
}
