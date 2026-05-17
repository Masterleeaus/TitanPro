<?php

namespace App\Models\Tz;

class TzCalendarSyncAccount extends AbstractTzTenantModel
{
    protected $table = 'tz_calendar_sync_accounts';

    protected function casts(): array
    {
        return [
            'token_json' => 'array',
            'last_synced_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
