<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Services\TitanCalendarSystem\CalendarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarInteractionController extends Controller
{
    public function __construct(protected CalendarService $calendarService)
    {
    }

    public function move(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['required', 'string'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
            'allDay' => ['nullable', 'boolean'],
        ]);

        return response()->json($this->calendarService->moveEvent($data));
    }
}
