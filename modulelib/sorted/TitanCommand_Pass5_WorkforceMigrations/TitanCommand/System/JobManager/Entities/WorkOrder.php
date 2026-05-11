<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;

    use HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;






class WorkOrder extends Model
{
    protected $fillable=[
        'wo_id',
        'wo_detail',
        'type',
        'client',
        'asset',
        'due_date',
        'status',
        'priority',
        'notes',
        'assign',
        'preferred_date',
        'preferred_time',
        'preferred_note',
        'parent_id',
    };

    public static $status=[
        'pending'=>'Pending',
        'approved'=>'Approved',
        'rejected'=>'Rejected',
        'on_hold'=>'On Hold',
        'cancelled'=>'Cancelled',
        'completed'=>'Completed',
    };

    public function clients()
    {
        return $this->hasOne('App\Extensions\TitanCommand\System\JobManager\Entities\User','id','client');
    }
    public function assigned()
    {
        return $this->hasOne('App\Extensions\TitanCommand\System\JobManager\Entities\User','id','assign');
    }

    public function assets()
    {
        return $this->hasOne('App\Extensions\TitanCommand\System\JobManager\Entities\Asset','id','asset');
    }
    public function types()
    {
        return $this->hasOne('App\Extensions\TitanCommand\System\JobManager\Entities\WOType','id','type');
    }

    public function serviceParts()
    {
        return $this->hasMany('App\Extensions\TitanCommand\System\JobManager\Entities\WOServicePart','wo_id','id');
    }
    public function getWorkorderTotalAmount()
    {
        $woTotal = 0;
        foreach ($this->serviceParts as $serviceParts) {
            $woTotal += $serviceParts->amount;
        }
        return $woTotal;
    }
    public function services()
    {
        return $this->hasMany('App\Extensions\TitanCommand\System\JobManager\Entities\WOServicePart','wo_id','id')->where('type','service');
    }
    public function parts()
    {
        return $this->hasMany('App\Extensions\TitanCommand\System\JobManager\Entities\WOServicePart','wo_id','id')->where('type','part');
    }

    public function tasks()
    {
        return $this->hasMany('App\Extensions\TitanCommand\System\JobManager\Entities\WOServiceTask','wo_id','id');
    }

    public function appointments()
    {
        return $this->hasOne('App\Extensions\TitanCommand\System\JobManager\Entities\WOServiceAppointment','wo_id','id');
    }

    public function client()
    {
        $class = config('jobmanager.models.client', \App\Models\Client::class);
        return $this->belongsTo($class, 'client_id');
    }

    public function technician()
    {
        $class = config('jobmanager.models.user', \App\Models\User::class);
        return $this->belongsTo($class, 'technician_id');
    }

    public function appointments()
    {
        return $this->hasMany(App\Extensions\TitanCommand\System\JobManager\Entities\WOServiceAppointment::class, 'work_order_id');
    }

    public function tasks()
    {
        return $this->hasMany(App\Extensions\TitanCommand\System\JobManager\Entities\WOServiceTask::class, 'work_order_id');
    }

    public function parts()
    {
        return $this->hasMany(App\Extensions\TitanCommand\System\JobManager\Entities\WOServicePart::class, 'work_order_id');
    }
    

    public function convertToProject(): ?object
    {
        $projectClass = config('jobmanager.models.project', \App\Models\Project::class);
        $taskClass = config('jobmanager.models.task', \App\Models\Task::class);

        if (!class_exists($projectClass) || !class_exists($taskClass)) {
            return null;
        }

        $project = $projectClass::create([
            'project_name' => 'WO #'.$this->id.' — '.$this->status,
            'client_id' => $this->client_id,
            'start_date' => now(),
            'deadline' => $this->due_by,
        ]);

        foreach ($this->tasks as $line) {
            $taskClass::create([
                'name' => 'Task: '.$line->service_task_id,
                'project_id' => $project->id,
                'start_date' => now(),
                'due_date' => $this->due_by,
                'hourly_rate' => $line->rate,
                'task_hour' => $line->qty,
            ]);
        }

        return $project;
    }
    
}
