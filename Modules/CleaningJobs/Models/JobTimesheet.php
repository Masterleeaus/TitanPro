<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTimesheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cleaning_job_timesheets';
    protected $fillable = ['work_order_id','task_id','user_id','work_date','hours','description','is_billable','billing_rate','cost_rate','billable_amount','cost_amount','status','approved_by_id','approved_at','company_id'];
    protected $casts = ['work_date' => 'date', 'hours' => 'decimal:2', 'is_billable' => 'boolean', 'billing_rate' => 'decimal:2', 'cost_rate' => 'decimal:2', 'billable_amount' => 'decimal:2', 'cost_amount' => 'decimal:2', 'approved_at' => 'datetime'];

    protected static function booted(): void
    {
        static::saving(function (JobTimesheet $timesheet) { $timesheet->calculateAmounts(); });
        static::saved(function (JobTimesheet $timesheet) { app(\Modules\CleaningJobs\Services\JobFinancialService::class)->syncWorkOrderFinancials($timesheet->workOrder); });
        static::deleted(function (JobTimesheet $timesheet) { app(\Modules\CleaningJobs\Services\JobFinancialService::class)->syncWorkOrderFinancials($timesheet->workOrder); });
    }

    public function calculateAmounts(): void
    {
        $this->cost_amount = (float) $this->hours * (float) $this->cost_rate;
        $this->billable_amount = $this->is_billable ? ((float) $this->hours * (float) $this->billing_rate) : 0;
    }

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function task() { return $this->belongsTo(JobTask::class, 'task_id'); }
    public function user() { return $this->belongsTo(config('cleaningjobs.models.user', \App\Models\User::class)); }
}
