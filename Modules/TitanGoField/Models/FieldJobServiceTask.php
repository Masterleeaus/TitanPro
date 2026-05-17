<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FieldJobServiceTask extends Model
{
    protected $table = 'field_job_service_tasks';

    protected $fillable = [
        'company_id', 'field_job_id', 'service_task_id',
        'task_name', 'qty', 'rate', 'total',
    ];

    protected $casts = [
        'qty'   => 'decimal:4',
        'rate'  => 'decimal:2',
        'total' => 'decimal:2',
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
