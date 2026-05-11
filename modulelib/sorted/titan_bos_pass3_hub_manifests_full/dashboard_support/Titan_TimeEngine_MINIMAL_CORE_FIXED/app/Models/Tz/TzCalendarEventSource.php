<?php

namespace App\Models\Tz;

class TzCalendarEventSource extends AbstractTzTenantModel
{
    protected $table = 'tz_calendar_event_sources';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'config_json' => 'array',
        ];
    }
}
