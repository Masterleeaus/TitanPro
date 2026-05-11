<?php

namespace App\Models\Tz;

class TzCalendarRecurrenceRule extends AbstractTzTenantModel
{
    protected $table = 'tz_calendar_recurrence_rules';

    protected function casts(): array
    {
        return [
            'days_of_week' => 'array',
            'until_at' => 'datetime',
        ];
    }
}
