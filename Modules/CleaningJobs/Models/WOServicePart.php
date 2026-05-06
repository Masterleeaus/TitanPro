<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOServicePart extends Model
{
    use HasFactory;

    protected $table = 'wo_service_parts';

    protected $fillable = [
        'wo_id', 'work_order_id', 'service_part_id', 'quantity', 'qty', 'amount',
        'price', 'total', 'type', 'description',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function serviceParts()
    {
        return $this->hasOne(ServicePart::class, 'id', 'service_part_id');
    }

    public function serviceTasks()
    {
        return $this->hasMany(ServiceTask::class, 'service_id', 'service_part_id');
    }
}
