<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Tz\TzCalendarSyncAccount;
use App\Services\TitanCalendarSystem\CalendarSyncService;
use App\Services\TitanCalendarSystem\GoogleCalendarService;
use Illuminate\Support\Facades\Schema;

class TitanCalendarSettingsController extends Controller
{
    public function __construct(protected CalendarSyncService $calendarSyncService, protected GoogleCalendarService $googleCalendarService)
    {
    }

    public function index()
    {
        $accounts = collect();
        if (Schema::hasTable('tz_calendar_sync_accounts')) {
            $accounts = TzCalendarSyncAccount::query()->latest('id')->limit(20)->get();
        }

        return view('panel.user.calendar.settings', [
            'syncSummary' => $this->calendarSyncService->summary(),
            'googleSummary' => $this->googleCalendarService->summary(),
            'syncAccounts' => $accounts,
        ]);
    }
}
