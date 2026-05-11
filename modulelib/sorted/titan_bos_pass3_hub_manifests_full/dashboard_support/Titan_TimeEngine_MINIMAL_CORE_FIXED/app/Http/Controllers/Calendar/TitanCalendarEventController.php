<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Services\TitanCalendarSystem\CalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TitanCalendarEventController extends Controller
{
    public function __construct(protected CalendarService $calendarService)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_type' => ['nullable', 'string', 'max:50'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'all_day' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'recurrence_frequency' => ['nullable', 'in:DAILY,WEEKLY,MONTHLY'],
            'recurrence_interval' => ['nullable', 'integer', 'min:1', 'max:90'],
            'recurrence_days' => ['nullable', 'array'],
            'recurrence_days.*' => ['string', 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun'],
            'recurrence_until' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'recurrence_count' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $data['all_day'] = (bool) ($data['all_day'] ?? false);
        $data['status'] = 'active';
        $data['source_type'] = 'manual';
        $this->calendarService->createManualEvent($data);

        return back()->with('success', __('Manual calendar event created.'));
    }
}
