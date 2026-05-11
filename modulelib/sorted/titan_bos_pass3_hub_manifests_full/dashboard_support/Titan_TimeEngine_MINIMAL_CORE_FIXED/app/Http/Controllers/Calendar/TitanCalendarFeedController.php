<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Services\TitanCalendarSystem\CalendarFeedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TitanCalendarFeedController extends Controller
{
    public function __construct(protected CalendarFeedService $calendarFeedService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        $filters = [
            'team_id' => data_get($user, 'active_team_id') ?: data_get($user, 'team_id') ?: data_get($user, 'company_id'),
            'start' => $request->string('start')->toString(),
            'end' => $request->string('end')->toString(),
            'source' => $request->string('source')->toString(),
            'status' => $request->string('status')->toString(),
            'event_type' => $request->string('event_type')->toString(),
        ];

        return response()->json([
            'success' => true,
            'events' => $this->calendarFeedService->feed($filters),
        ]);
    }
}
