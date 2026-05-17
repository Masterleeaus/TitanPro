<?php

namespace App\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Models\TitanZeroThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;

/**
 * POST /api/titan/zero/generate-ui
 *
 * Accepts a chat message from the Business OS panel, routes it through the AI
 * pipeline (GeneratorBridge when available, direct OpenAI otherwise), persists
 * the conversation to a TitanZeroThread, and returns an AgentUiResponse.
 */
class GenerateUiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message'          => ['required', 'string', 'max:4000'],
            'threadId'         => ['nullable', 'integer', 'min:1'],
            'context'          => ['nullable', 'array'],
            'context.appKey'   => ['nullable', 'string', 'max:80'],
            'context.page'     => ['nullable', 'string', 'max:255'],
        ]);

        $user    = Auth::user();
        $orgId   = $user?->organization_id;
        $userId  = $user?->id;
        $message = trim($validated['message']);
        $context = $validated['context'] ?? [];
        $appKey  = $context['appKey'] ?? 'default';
        $page    = $context['page']   ?? '';

        // ── Find or create thread ────────────────────────────────────────────
        $thread = null;
        if (! empty($validated['threadId'])) {
            $thread = TitanZeroThread::query()
                ->where('id', $validated['threadId'])
                ->first();
        }

        if (! $thread) {
            $thread = new TitanZeroThread([
                'organization_id' => $orgId,
                'user_id'         => $userId,
                'app_key'         => $appKey,
                'title'           => null,
                'messages'        => [],
                'widgets'         => [],
            ]);
        }

        // Auto-title from first user message
        if (! $thread->title) {
            $thread->title = Str::limit($message, 60);
        }

        // Persist user message
        $userMessageId = (string) Str::uuid();
        $thread->appendMessage([
            'id'        => $userMessageId,
            'role'      => 'user',
            'content'   => $message,
            'createdAt' => now()->toISOString(),
        ]);

        // ── Build system prompt ──────────────────────────────────────────────
        $portalSnapshot = $this->portalSnapshot($appKey, $context, (int) ($orgId ?? 0));
        $systemPrompt = $this->buildSystemPrompt($appKey, $page, $portalSnapshot);

        // ── AI generation ────────────────────────────────────────────────────
        [$aiText, $parts] = $this->generate($message, $systemPrompt, $thread->messages ?? []);

        // ── Persist assistant message ────────────────────────────────────────
        $assistantMessageId = (string) Str::uuid();
        $thread->appendMessage([
            'id'        => $assistantMessageId,
            'role'      => 'assistant',
            'content'   => $aiText,
            'createdAt' => now()->toISOString(),
            'widgets'   => $parts,
        ]);

        if (! empty($parts)) {
            $thread->widgets = $parts;
        }

        $thread->save();

        // ── Build suggestions ────────────────────────────────────────────────
        $suggestions = SuggestionsController::suggestionsForAppKey($appKey);

        return response()->json([
            'is_task_complete' => true,
            'message'          => $aiText,
            'parts'            => $parts,
            'errors'           => [],
            'meta'             => [
                'threadId'    => (string) $thread->id,
                'suggestions' => $suggestions,
            ],
        ]);
    }

    // ── Internals ────────────────────────────────────────────────────────────

    private function buildSystemPrompt(string $appKey, string $page, array $portalSnapshot = []): string
    {
        $snapshotBlock = '';
        if ($appKey === 'portal' && $portalSnapshot !== []) {
            $snapshotBlock = "\nPortal customer data snapshot:\n" . json_encode($portalSnapshot, JSON_PRETTY_PRINT) . "\n";
        }

        return <<<PROMPT
You are Titan Zero, the intelligent AI assistant embedded in the Business OS platform.
Current context: app_key={$appKey}, page={$page}
{$snapshotBlock}

Respond ONLY with valid JSON matching this exact structure:
{
  "message": "<brief friendly summary (1-2 sentences)>",
  "parts": [
    {
      "id": "<unique-widget-id>",
      "kind": "<metric-card|line-chart|bar-chart|data-table|log-list>",
      "title": "<widget title>",
      "data": {}
    }
  ]
}

Rules:
- Always include at least one widget in "parts" relevant to the user's query.
- Use metric-card for single KPI values, data-table for lists, line-chart/bar-chart for trends.
- Keep "message" conversational and helpful.
- Do NOT include any text outside the JSON object.
PROMPT;
    }

    private function portalSnapshot(string $appKey, array $context, int $defaultCompanyId): array
    {
        if ($appKey !== 'portal' || ! class_exists(WorkcorePortalDataService::class)) {
            return [];
        }

        $customerId = (int) ($context['customerId'] ?? $context['customer_id'] ?? 0);
        $companyId = (int) ($context['companyId'] ?? $context['company_id'] ?? $defaultCompanyId);
        if ($customerId <= 0 || $companyId <= 0) {
            return [];
        }

        try {
            /** @var WorkcorePortalDataService $workcore */
            $workcore = app(WorkcorePortalDataService::class);
            $snapshot = $workcore->getCustomerProfile($customerId, $companyId);
            if ($snapshot === []) {
                return [];
            }

            $snapshot['upcoming'] = $workcore->getUpcomingVisits($customerId, $companyId);
            $snapshot['balance'] = $workcore->getOutstandingBalance($customerId, $companyId);
            $snapshot['invoices'] = $workcore->getInvoices($customerId, $companyId);
            $snapshot['quotes'] = $workcore->getQuotes($customerId, $companyId);

            $snapshotJson = json_encode($snapshot);
            if (is_string($snapshotJson) && strlen($snapshotJson) > 5000) {
                $snapshot['upcoming'] = array_slice($snapshot['upcoming'], 0, 3);
                $snapshot['invoices'] = array_slice($snapshot['invoices'], 0, 3);
                $snapshot['quotes'] = array_slice($snapshot['quotes'], 0, 3);
                Log::info('GenerateUiController: portal snapshot trimmed due to size', [
                    'customer_id' => $customerId,
                    'company_id' => $companyId,
                    'size' => strlen($snapshotJson),
                ]);
            }

            return $snapshot;
        } catch (\Throwable $e) {
            Log::warning('GenerateUiController: unable to build portal snapshot', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Attempt AI generation via GeneratorBridge (if available) or OpenAI directly.
     * Falls back to a safe canned response so the endpoint always returns a valid shape.
     *
     * @param  array<int, array<string, mixed>>  $history
     * @return array{0: string, 1: list<array<string, mixed>>}
     */
    /** Number of recent history messages to include as AI context. */
    private const CONTEXT_MESSAGE_LIMIT = 6;

    private function generate(string $message, string $systemPrompt, array $history): array
    {
        // 1. Try GeneratorBridge from TitanEchoAssist module (safe lazy resolution)
        if (class_exists(\Modules\TitanEchoAssist\Services\GeneratorBridge::class)) {
            try {
                /** @var \Modules\TitanEchoAssist\Services\GeneratorBridge $bridge */
                $bridge = app(\Modules\TitanEchoAssist\Services\GeneratorBridge::class);

                // Build context messages from history for the bridge
                $contextMessages = array_map(fn ($m) => [
                    'role'    => $m['role'] ?? 'user',
                    'content' => $m['content'] ?? '',
                ], array_slice($history, -self::CONTEXT_MESSAGE_LIMIT));

                // Use a fake chatbot config so the bridge injects our system prompt
                $bridge->setChatbot(['instructions' => $systemPrompt]);
                $raw = $bridge->generate($message, $contextMessages);

                return $this->parseAiResponse($raw, $message);
            } catch (\Throwable $e) {
                Log::warning('GenerateUiController: GeneratorBridge failed.', ['error' => $e->getMessage()]);
            }
        }

        // 2. Direct OpenAI call
        $apiKey = config('titan-chatbot.ai.openai_api_key', config('openai.api_key', env('OPENAI_API_KEY')));
        $model  = config('titan-chatbot.ai.model', 'gpt-4o-mini');

        if ($apiKey) {
            try {
                $messages = [['role' => 'system', 'content' => $systemPrompt]];

                foreach (array_slice($history, -self::CONTEXT_MESSAGE_LIMIT) as $m) {
                    $messages[] = ['role' => $m['role'] ?? 'user', 'content' => $m['content'] ?? ''];
                }

                $messages[] = ['role' => 'user', 'content' => $message];

                $response = Http::withToken($apiKey)
                    ->timeout(15)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model'       => $model,
                        'messages'    => $messages,
                        'temperature' => 0.4,
                    ]);

                if ($response->successful()) {
                    $raw = $response->json('choices.0.message.content', '');
                    return $this->parseAiResponse($raw, $message);
                }

                Log::warning('GenerateUiController: OpenAI request failed.', ['status' => $response->status()]);
            } catch (\Throwable $e) {
                Log::warning('GenerateUiController: OpenAI call threw.', ['error' => $e->getMessage()]);
            }
        }

        // 3. Safe fallback — return a generic text response with a placeholder widget
        return $this->fallbackResponse($message);
    }

    /**
     * Parse an AI text response into (message, parts).
     * If the response is valid JSON matching our schema, extract both fields.
     * Otherwise wrap the raw text in a simple log-list widget.
     *
     * @return array{0: string, 1: list<array<string, mixed>>}
     */
    private function parseAiResponse(string $raw, string $originalMessage): array
    {
        $raw = trim($raw);

        // Strip markdown code fences if the model added them
        $raw = preg_replace('/^```(?:json)?\s*/i', '', $raw) ?? $raw;
        $raw = preg_replace('/\s*```$/', '', $raw) ?? $raw;

        $decoded = json_decode(trim($raw), true);

        if (is_array($decoded) && isset($decoded['message'])) {
            $text  = (string) ($decoded['message'] ?? '');
            $parts = $this->normalizeParts($decoded['parts'] ?? []);

            return [$text ?: $originalMessage, $parts];
        }

        // Plain text response — wrap in a generic widget
        return [
            $raw ?: 'Here is what I found.',
            [
                [
                    'id'    => 'w-' . Str::random(6),
                    'kind'  => 'log-list',
                    'title' => 'Response',
                    'data'  => ['text' => $raw],
                ],
            ],
        ];
    }

    /**
     * Ensure each part has an id and valid kind; unknown kinds default to metric-card.
     *
     * @param  mixed  $parts
     * @return list<array<string, mixed>>
     */
    private function normalizeParts(mixed $parts): array
    {
        if (! is_array($parts)) {
            return [];
        }

        $validKinds = ['metric-card', 'line-chart', 'bar-chart', 'data-table', 'log-list', 'chat-thread', 'tool-call', 'settings-form', 'mcp-server-list', 'project-list'];

        return array_values(array_filter(array_map(function ($part) use ($validKinds) {
            if (! is_array($part)) {
                return null;
            }
            return [
                'id'    => (string) ($part['id'] ?? Str::random(8)),
                'kind'  => in_array($part['kind'] ?? '', $validKinds, true) ? $part['kind'] : 'metric-card',
                'title' => $part['title'] ?? null,
                'data'  => $part['data'] ?? null,
                'props' => $part['props'] ?? null,
            ];
        }, $parts)));
    }

    /**
     * Safe fallback when AI is unavailable.
     *
     * @return array{0: string, 1: list<array<string, mixed>>}
     */
    private function fallbackResponse(string $message): array
    {
        return [
            'Titan Zero is connected to the Business OS. How can I help you today?',
            [
                [
                    'id'    => 'w-status',
                    'kind'  => 'metric-card',
                    'title' => 'Titan Zero',
                    'data'  => ['value' => 'Ready', 'label' => 'AI assistant online'],
                ],
            ],
        ];
    }
}
