<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class CustomerLocation extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'customer_locations';

    protected $fillable = [
        'company_id', 'customer_id', 'name', 'address_line_1', 'address_line_2', 'suburb', 'state', 'postcode',
        'country', 'latitude', 'longitude', 'access_notes', 'parking_notes', 'service_zone_id', 'active'
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'latitude' => 'decimal:7', 'longitude' => 'decimal:7'];
    }

    public function customer()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'customer_id');
    }

    public function serviceZone()
    {
        return $this->belongsTo(ServiceZone::class, 'service_zone_id');
    }

    public function routeStops()
    {
        return $this->hasMany(DispatchRouteStop::class, 'customer_location_id');
    }

    public function getFullAddressAttribute(): string
    {
        return collect([$this->address_line_1, $this->address_line_2, $this->suburb, $this->state, $this->postcode, $this->country])
            ->filter()
            ->implode(', ');
    }
}
