<?php

namespace App\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Models\TitanZeroThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        'portal' => [
            'Show upcoming visits',
            'Check invoice balance',
            'Show recent activity',
            'Open customer profile',
            'Explain this screen',
        ],
        'default' => [
            'Open app',
            'Search workspace',
            'Explain this screen',
            'Show recent activity',
            'Help me navigate',
        ],
    ];

    public function __invoke(Request $request): JsonResponse
    {
        $thread = $this->resolveThread($request);
        $appKey = $this->resolveAppKey($request, $thread);

        $suggestions = self::suggestionsForAppKey((string) $appKey);

        if ($request->hasSession()) {
            $request->session()->put('titan_zero.context', [
                'app_key' => $appKey,
            ]);
        }

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

    private function resolveThread(Request $request): ?TitanZeroThread
    {
        $threadId = trim((string) $request->query('threadId', ''));

        if ($threadId === '') {
            return null;
        }

        $thread = TitanZeroThread::query()
            ->withoutGlobalScopes()
            ->whereKey($threadId)
            ->firstOrFail();

        if ((int) $thread->organization_id !== (int) $request->user()?->organization_id) {
            throw new AccessDeniedHttpException('You do not have access to this thread.');
        }

        return $thread;
    }

    private function resolveAppKey(Request $request, ?TitanZeroThread $thread): string
    {
        $appKey = trim((string) $request->query('appKey', ''));

        if ($appKey !== '') {
            return $appKey;
        }

        $headerContext = $this->headerContext($request);
        $appKey = trim((string) ($headerContext['app_key'] ?? $headerContext['appKey'] ?? ''));

        if ($appKey !== '') {
            return $appKey;
        }

        $appKey = trim((string) ($thread?->app_key ?? ''));

        if ($appKey !== '') {
            return $appKey;
        }

        return trim((string) data_get($request->session()->get('titan_zero.context', []), 'app_key', 'default')) ?: 'default';
    }

    /**
     * @return array<string, mixed>
     */
    private function headerContext(Request $request): array
    {
        $header = $request->header('context', $request->header('X-Titan-Context', ''));

        if (is_array($header)) {
            return [];
        }

        $header = trim((string) $header);

        if ($header === '') {
            $appKey = trim((string) $request->header('X-Titan-App-Key', ''));

            return $appKey === '' ? [] : ['app_key' => $appKey];
        }

        $decoded = json_decode($header, true);

        return is_array($decoded) ? $decoded : ['app_key' => $header];
    }
}
