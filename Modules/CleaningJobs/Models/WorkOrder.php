<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $table = 'work_orders';

    protected $fillable = [
        // Legacy Worksuite fields
        'wo_id', 'wo_detail', 'type', 'client', 'asset', 'due_date', 'status',
        'priority', 'notes', 'assign', 'preferred_date', 'preferred_time',
        'preferred_note', 'parent_id',
        // SaaS/job-management fields introduced by the modern scaffolding
        'client_id', 'technician_id', 'scheduled_for', 'due_by', 'total_estimate',
        'location', 'title', 'description',
        'budget_amount', 'actual_cost', 'actual_revenue', 'estimated_hours', 'actual_hours', 'health',
    ];

    public static $status = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'on_hold' => 'On Hold',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
        'done' => 'Done',
    ];

    public function clients()
    {
        return $this->hasOne(config('cleaningjobs.models.user', \App\Models\User::class), 'id', 'client');
    }

    public function assigned()
    {
        return $this->hasOne(config('cleaningjobs.models.user', \App\Models\User::class), 'id', 'assign');
    }

    public function assets()
    {
        $class = 'Modules\\CleaningJobs\\Models\\Asset';
        return class_exists($class) ? $this->hasOne($class, 'id', 'asset') : $this->hasOne(static::class, 'id', 'asset')->whereRaw('1 = 0');
    }

    public function types()
    {
        return $this->hasOne(WOType::class, 'id', 'type');
    }

    public function client()
    {
        return $this->belongsTo(config('cleaningjobs.models.client', config('cleaningjobs.models.user', \App\Models\User::class)), 'client_id');
    }

    public function technician()
    {
        return $this->belongsTo(config('cleaningjobs.models.user', \App\Models\User::class), 'technician_id');
    }

    public function serviceParts()
    {
        return $this->hasMany(WOServicePart::class, 'wo_id', 'id')
            ->orWhere('work_order_id', $this->id);
    }

    public function appointments()
    {
        return $this->hasMany(WOServiceAppointment::class, 'work_order_id', 'id')
            ->orWhere('wo_id', $this->id);
    }

    public function tasks()
    {
        return $this->hasMany(WOServiceTask::class, 'work_order_id', 'id')
            ->orWhere('wo_id', $this->id);
    }

    public function parts()
    {
        return $this->hasMany(WOServicePart::class, 'work_order_id', 'id')
            ->orWhere('wo_id', $this->id)
            ->where(function ($query) {
                $query->whereNull('type')->orWhere('type', 'part');
            });
    }

    public function services()
    {
        return $this->hasMany(WOServicePart::class, 'work_order_id', 'id')
            ->orWhere('wo_id', $this->id)
            ->where('type', 'service');
    }



    public function jobTasks()
    {
        return $this->hasMany(JobTask::class, 'work_order_id');
    }

    public function milestones()
    {
        return $this->hasMany(JobMilestone::class, 'work_order_id')->orderBy('order');
    }

    public function timesheets()
    {
        return $this->hasMany(JobTimesheet::class, 'work_order_id');
    }

    public function resourceAllocations()
    {
        return $this->hasMany(JobResourceAllocation::class, 'work_order_id');
    }

    public function comments()
    {
        return $this->morphMany(JobComment::class, 'commentable')->latest();
    }

    public function files()
    {
        return $this->morphMany(JobFile::class, 'fileable')->latest();
    }

    public function getWorkorderTotalAmount()
    {
        return $this->serviceParts->sum(function ($part) {
            return (float) ($part->total ?? $part->amount ?? 0);
        });
    }

    public function convertToProject(): ?object
    {
        $projectClass = config('cleaningjobs.models.project', \App\Models\Project::class);
        $taskClass = config('cleaningjobs.models.task', \App\Models\Task::class);

        if (!class_exists($projectClass) || !class_exists($taskClass)) {
            return null;
        }

        $project = $projectClass::create([
            'project_name' => 'WO #'.$this->id.' — '.($this->wo_detail ?? $this->status ?? 'Cleaning Job'),
            'client_id' => $this->client_id ?? $this->client,
            'start_date' => now(),
            'deadline' => $this->due_by ?? $this->due_date,
        ]);

        foreach ($this->tasks as $line) {
            $taskClass::create([
                'name' => 'Task: '.($line->service_task ?? $line->service_task_id ?? $line->id),
                'project_id' => $project->id,
                'start_date' => now(),
                'due_date' => $this->due_by ?? $this->due_date,
                'hourly_rate' => $line->rate ?? 0,
                'task_hour' => $line->qty ?? 1,
            ]);
        }

        return $project;
    }
}
