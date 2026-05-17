<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobAppointment extends Model
{
    protected $table = 'field_job_appointments';

    protected $fillable = [
        'company_id', 'field_job_id', 'technician_id',
        'starts_at', 'ends_at', 'location', 'status', 'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
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
