<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FsmSlaProfile extends Model
{
    protected $table = 'fsm_sla_profiles';

    protected $fillable = [
        'company_id', 'name', 'vertical',
        'arrival_minutes', 'completion_minutes', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }
}
