<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobType extends Model
{
    protected $table = 'field_job_types';

    protected $fillable = ['company_id', 'name', 'color', 'description'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function jobs()
    {
        return $this->hasMany(FieldJob::class, 'type_id');
    }
}
