<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class Shift extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'company_id',
        'name',
        'shift_date',
        'type',
        'cyclic_duration',
        'start_min_time',
        'start_time',
        'start_max_time',
        'finish_min_time',
        'finish_time',
        'finish_max_time',
        'break_time',
        'free_work_time',
        'free_work_time_range',
        'free_work_time_from',
        'free_work_time_to',
        'range',
        'range_from',
        'range_to',
        'unhealthy_shift',
        'weekdays',
        'indefinite',
        'shift_end_on',
        'project_id',
        'task_id',
        'tag',
        'note',
        'publish',
    ];

    protected function casts(): array
    {
        return [
            'company_id' => 'integer',
            'type' => 'integer',
            'cyclic_duration' => 'integer',
            'break_time' => 'integer',
            'free_work_time' => 'integer',
            'free_work_time_range' => 'integer',
            'range' => 'integer',
            'unhealthy_shift' => 'integer',
            'indefinite' => 'integer',
            'project_id' => 'integer',
            'task_id' => 'integer',
            'publish' => 'integer',
        ];
    }

    public function assignments()
    {
        return $this->hasMany(AssignShift::class, 'shift_id');
    }
}
