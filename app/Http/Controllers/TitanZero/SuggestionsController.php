<?php

namespace App\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Models\TitanZeroThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * GET /api/titan/suggestions
 *
 * Returns context-aware suggestion chips for the Business OS chat panel.
 * Chips are chosen by a static map per appKey; future passes can evolve
 * this to AI-generated suggestions based on the last thread intent.
 */
class SuggestionsController extends Controller
{
    /**
     * Static suggestion mapping per appKey.
     *
     * @var array<string, list<string>>
     */
    private const APP_SUGGESTIONS = [
        'owner' => [
            'Show jobs today',
            'Show overdue invoices',
            'Revenue this week',
            'Open dispatch map',
            'Summarise open quotes',
        ],
        'technician' => [
            'My jobs today',
            'Navigate to next job',
            'Show job checklist',
            'Log a note on current job',
        ],
        'admin' => [
            'Show team workload',
            'Pending approvals',
            'Revenue this month',
            'Active customers',
        ],
        'default' => [
            'Show jobs today',
            'Open ZeroPay',
            'Revenue this week',
            'Active customers',
            'Show overdue invoices',
        ],
    ];

    public function __invoke(Request $request): JsonResponse
    {
        $appKey   = $request->query('appKey', 'default');
        $threadId = $request->query('threadId');

        $suggestions = self::suggestionsForAppKey((string) $appKey);

        return response()->json(['suggestions' => $suggestions]);
    }

    /**
     * Return 3-5 suggestion chips for the given appKey.
     *
     * @return list<string>
     */
    public static function suggestionsForAppKey(string $appKey): array
    {
        $map = self::APP_SUGGESTIONS;

        $suggestions = $map[$appKey] ?? $map['default'];

        return array_slice($suggestions, 0, 5);
    }
}
