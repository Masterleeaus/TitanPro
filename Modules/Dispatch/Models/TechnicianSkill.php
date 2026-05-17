<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class TechnicianSkill extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'technician_skills';

    protected $fillable = ['company_id', 'name', 'description', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function technicians()
    {
        return $this->belongsToMany(TechnicianProfile::class, 'technician_profile_skill')
            ->withPivot(['company_id', 'level'])
            ->withTimestamps();
    }
}
