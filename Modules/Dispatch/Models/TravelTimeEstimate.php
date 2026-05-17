<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class TravelTimeEstimate extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'travel_time_estimates';

    protected $fillable = [
        'company_id',
        'origin_location_id',
        'destination_location_id',
        'distance_meters',
        'duration_seconds',
        'provider',
        'calculated_at',
        'metadata'
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'calculated_at' => 'datetime',
        ];
    }
}
