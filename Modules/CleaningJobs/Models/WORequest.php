<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WORequest extends Model
{
    use HasFactory;

    protected $table = 'wo_requests';

    protected $fillable = [
        'work_order_id', 'requested_by_id', 'channel', 'description',
        'request_detail', 'client', 'asset', 'priority', 'due_date', 'status',
        'assign', 'notes', 'preferred_date', 'preferred_time', 'preferred_note', 'parent_id',
    ];

    public static $priority = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical',
    ];

    public static $status = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancel' => 'Cancel',
    ];

    public static $time = [
        'any_time' => 'Any Time',
        'morning' => 'Morning',
        'afternoon' => 'Afternoon',
        'evening' => 'Evening',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

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
}
