<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchAppointment extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'dispatch_appointments';

    protected $fillable = [
        'company_id', 'work_order_id', 'technician_id', 'shift_id', 'customer_location_id', 'starts_at', 'ends_at',
        'start_date', 'start_time', 'end_date', 'end_time', 'location', 'status', 'checked_in_at', 'checked_out_at', 'notes', 'completion_notes', 'photo_paths', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'photo_paths' => 'array',
            'metadata' => 'array',
        ];
    }

    public function workOrder()
    {
        return $this->belongsTo(DispatchWorkOrder::class, 'work_order_id');
    }

    public function technician()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'technician_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function customerLocation()
    {
        return $this->belongsTo(CustomerLocation::class, 'customer_location_id');
    }

    public function shiftAssignment()
    {
        return $this->hasOne(AssignShift::class, 'appointment_id');
    }
}
