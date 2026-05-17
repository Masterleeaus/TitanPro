<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Database\Factories\DispatchWorkOrderFactory;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchWorkOrder extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'dispatch_work_orders';

    protected $fillable = [
        'company_id', 'customer_id', 'customer_location_id', 'technician_id', 'title', 'reference', 'status',
        'priority', 'service_type', 'description', 'notes', 'location', 'estimated_hours', 'scheduled_for', 'arrival_window_start', 'arrival_window_end', 'started_at',
        'completed_at', 'metadata',
    ];

    protected static function newFactory(): DispatchWorkOrderFactory
    {
        return DispatchWorkOrderFactory::new();
    }

    protected function casts(): array
    {
        return [
            'company_id' => 'integer',
            'customer_id' => 'integer',
            'customer_location_id' => 'integer',
            'technician_id' => 'integer',
            'estimated_hours' => 'decimal:2',
            'scheduled_for' => 'datetime',
            'arrival_window_start' => 'datetime',
            'arrival_window_end' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'technician_id');
    }

    public function customerLocation()
    {
        return $this->belongsTo(CustomerLocation::class, 'customer_location_id');
    }

    public function appointments()
    {
        return $this->hasMany(DispatchAppointment::class, 'work_order_id');
    }

    public function primaryShiftAssignment()
    {
        return $this->hasOne(AssignShift::class, 'work_order_id')->latestOfMany();
    }

    public function checklists()
    {
        return $this->hasMany(DispatchChecklist::class, 'work_order_id');
    }

    public function exceptions()
    {
        return $this->hasMany(DispatchException::class, 'work_order_id');
    }

    public function openExceptions()
    {
        return $this->hasMany(DispatchException::class, 'work_order_id')->where('status', 'open');
    }
}

