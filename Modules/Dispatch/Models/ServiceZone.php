<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class ServiceZone extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'service_zones';

    protected $fillable = ['company_id', 'name', 'code', 'description', 'polygon', 'center_latitude', 'center_longitude', 'active'];

    protected function casts(): array
    {
        return ['polygon' => 'array', 'active' => 'boolean', 'center_latitude' => 'decimal:7', 'center_longitude' => 'decimal:7'];
    }

    public function technicians()
    {
        return $this->hasMany(TechnicianProfile::class, 'default_zone_id');
    }

    public function customerLocations()
    {
        return $this->hasMany(CustomerLocation::class, 'service_zone_id');
    }
}
