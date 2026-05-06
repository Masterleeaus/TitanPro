<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobResourceCapacity extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_resource_capacities';
    protected $fillable = ['user_id','date','available_hours','allocated_hours','utilized_hours','is_working_day','leave_type','notes'];
    protected $casts = ['date' => 'date', 'available_hours' => 'decimal:2', 'allocated_hours' => 'decimal:2', 'utilized_hours' => 'decimal:2', 'is_working_day' => 'boolean'];

    public function getRemainingHoursAttribute(): float
    {
        return max(0, (float) $this->available_hours - (float) $this->allocated_hours);
    }
}
