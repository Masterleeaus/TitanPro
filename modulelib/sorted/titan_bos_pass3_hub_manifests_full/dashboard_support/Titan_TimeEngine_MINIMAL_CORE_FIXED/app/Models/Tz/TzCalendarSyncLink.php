<?php

namespace App\Models\Tz;

class TzCalendarSyncLink extends AbstractTzTenantModel
{
    protected $table = 'tz_calendar_sync_links';

    protected function casts(): array
    {
        return [
            'last_push_at' => 'datetime',
            'last_pull_at' => 'datetime',
        ];
    }
}
