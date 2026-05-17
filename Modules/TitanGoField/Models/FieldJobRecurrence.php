<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobRecurrence extends Model
{
    protected $table = 'field_job_recurrences';

    protected $fillable = [
        'company_id', 'field_job_id', 'rrule',
        'starts_at', 'ends_at', 'next_run_at', 'last_run_at', 'is_active',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'ends_at'     => 'datetime',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function fieldJob()
    {
        return $this->belongsTo(FieldJob::class, 'field_job_id');
    }
}
