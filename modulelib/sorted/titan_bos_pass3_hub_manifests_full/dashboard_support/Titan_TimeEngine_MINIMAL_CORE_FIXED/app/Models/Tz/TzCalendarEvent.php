<?php

namespace App\Models\Tz;

class TzCalendarEvent extends AbstractTzTenantModel
{
    protected $table = 'tz_calendar_events';

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
            'meta_json' => 'array',
        ];
    }
}
