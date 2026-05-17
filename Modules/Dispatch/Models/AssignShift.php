<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class AssignShift extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'assign_shifts';

    protected $fillable = [
        'company_id',
        'department_id',
        'color',
        'shift_id',
        'employee_id',
        'extra_hours',
        'publish',
        'date_added',
        'month_added',
        'year_added',
        'work_order_id',
        'appointment_id',
        'dispatch_status',
        'dispatch_notes',
    ];

    protected function casts(): array
    {
        return [
            'company_id' => 'integer',
            'shift_id' => 'integer',
            'employee_id' => 'integer',
            'extra_hours' => 'integer',
            'publish' => 'integer',
            'work_order_id' => 'integer',
            'appointment_id' => 'integer',
        ];
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function employee()
    {
        return $this->belongsTo(config('dispatch.models.user', \App\Models\User::class), 'employee_id');
    }

    public function technician()
    {
        return $this->employee();
    }

    public function workOrder()
    {
        return $this->belongsTo(DispatchWorkOrder::class, 'work_order_id');
    }

    public function appointment()
    {
        return $this->belongsTo(DispatchAppointment::class, 'appointment_id');
    }
}
