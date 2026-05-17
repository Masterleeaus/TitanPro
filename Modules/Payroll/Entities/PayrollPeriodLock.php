<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class PayrollPeriodLock extends Model
{
    protected $guarded = [];

    protected $casts = [
        'period_from' => 'date',
        'period_to' => 'date',
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
        'metadata' => 'array',
    ];
}
