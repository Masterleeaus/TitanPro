<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Database\Factories\TechnicianProfileFactory;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class TechnicianProfile extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'technician_profiles';

    protected $fillable = [
        'company_id', 'user_id', 'display_name', 'phone', 'home_base_latitude', 'home_base_longitude',
        'default_zone_id', 'capacity_minutes_per_day', 'active'
    ];

    protected static function newFactory(): TechnicianProfileFactory
    {
        return TechnicianProfileFactory::new();
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'capacity_minutes_per_day' => 'integer',
            'home_base_latitude' => 'decimal:7',
            'home_base_longitude' => 'decimal:7',
        ];
    }

    public function user()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'user_id');
    }

    public function defaultZone()
    {
        return $this->belongsTo(ServiceZone::class, 'default_zone_id');
    }

    public function skills()
    {
        return $this->belongsToMany(TechnicianSkill::class, 'technician_profile_skill')
            ->withPivot(['company_id', 'level'])
            ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(AssignShift::class, 'employee_id', 'user_id');
    }

    public function routes()
    {
        return $this->hasMany(DispatchRoute::class, 'technician_id', 'user_id');
    }
}
