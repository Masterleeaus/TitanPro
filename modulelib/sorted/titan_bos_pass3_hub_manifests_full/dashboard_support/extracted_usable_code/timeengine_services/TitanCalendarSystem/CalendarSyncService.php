<?php

namespace App\Services\TitanCalendarSystem;

use Illuminate\Support\Facades\Schema;

class CalendarSyncService
{
    public function summary(): array
    {
        return [
            'sync_accounts_table' => Schema::hasTable('tz_calendar_sync_accounts'),
            'sync_links_table' => Schema::hasTable('tz_calendar_sync_links'),
            'sync_mode' => 'manual_mvp',
            'providers' => ['google'],
        ];
    }
}
