<?php

namespace Modules\TitanZero\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\AICore\Contracts\AI\ClientInterface as TitanClient;
use Modules\TitanEchoAssist\Services\GeneratorBridge;
use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use Modules\TitanZero\Entities\TitanZeroUsage;
use Modules\TitanCore\Services\UsageCostLogger;

class TitanZeroService
{
    private const MAX_PORTAL_SNAPSHOT_BYTES = 5000;
    private const UI_CONTEXT_MESSAGE_LIMIT = 6;

    /**
     * Titan Zero service talks to Titan Core on behalf of the module.
     */
    public function __construct(protected TitanClient $client, protected UsageCostLogger $usage)
    {
    }

    /**
     * Generate AI content for a given prompt.
     *
     * The model is selected at Super Admin level only via config('aiassistant.model').
     *
     * @param  string  $prompt
     * @param  string  $language
     * @param  int     $maxTokens
     * @param  float   $temperature
     * @param  int     $maxResults
     * @return array{success: bool, text?: string, tokens?: int|null, message?: string}
     */
    public function generate(
        string $prompt,
        string $language,
        int $maxTokens,
        float $temperature,
        int $maxResults = 1,
        ?int $userId = null,
        ?int $companyId = null,
        ?int $templateId = null
    ): array
    {
        $langText = "Provide response in {$language} language.\n\n ";

        $model = config('aiassistant.model', 'gpt-5-nano');

        try {
            $result = $this->client->chat([
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => $prompt . ' ' . $langText,
                    ],
                ],
                'model'       => $model,
                'temperature' => $temperature,
                'max_tokens'  => $maxTokens,
                'n'           => $maxResults,
            ]);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => __('Text was not generated due to Invalid API Key'),
            ];
        }

        if (is_string($result)) {
            $response = json_decode($result, true);
        } else {
            $response = $result;
        }

        if (! is_array($response) || ! isset($response['choices'])) {
            return [
                'success' => false,
                'message' => __('Text was not generated due to Invalid API Key'),
            ];
        }

        $text = '';
        $counter = 1;

        if (count($response['choices']) > 1) {
            foreach ($response['choices'] as $value) {
                $choiceText = $value['message']['content'] ?? ($value['text'] ?? '');
                $text      .= $counter . '. ' . ltrim($choiceText) . "\r\n\r\n\r\n";
                $counter++;
            }
        } else {
            $choiceText = $response['choices'][0]['message']['content']
                ?? ($response['choices'][0]['text'] ?? '');
            $text = $choiceText;
        }

        $tokens = $response['usage']['completion_tokens'] ?? ($response['usage']['total_tokens'] ?? null);


        // Cost + token telemetry (tenant scoped).
        try {
            $this->usage->logFromOpenAIResponse('chat', $response, [
                'tenant_id' => $companyId, // Worksuite often uses company_id as tenant proxy here
                'user_id' => $userId,
                'agent_slug' => 'titan_zero',
                'provider' => 'openai',
                'model' => $model,
                'template_id' => $templateId,
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        // Log lightweight usage row for reporting/limits.
        try {
            TitanZeroUsage::create([
                'user_id'        => $userId,
                'company_id'     => $companyId,
                'template_id'    => $templateId,
                'tokens_used'    => $tokens ?? 0,
                'requests_count' => 1,
            ]);
        } catch (\Throwable $e) {
            // Failing to log should not break user flow.
        }

        return [
            'success' => true,
            'text'    => $text,
            'tokens'  => $tokens,
        ];
    }

    /**
     * Build a Business OS assistant response with normalized widgets.
     *
     * @param  array<int, array<string, mixed>>  $history
     * @param  array<string, mixed>  $context
     * @return array{message: string, reply: string, parts: list<array<string, mixed>>, widgets: list<array<string, mixed>>}
     */
    public function respond(string $message, array $history = [], array $context = []): array
    {
        $message = trim($message);
        $appKey = (string) ($context['appKey'] ?? $context['app_key'] ?? 'default');
        $page = (string) ($context['page'] ?? '');
        $companyId = (int) ($context['companyId'] ?? $context['company_id'] ?? $context['organization_id'] ?? 0);

        $prompt = $this->buildBusinessOsPrompt(
            $appKey,
            $page,
            $this->portalSnapshot($appKey, $context, $companyId)
        );

        [$reply, $parts] = $this->generateUiResponse($message, $prompt, $history);
        $widgets = app(WidgetFactory::class)->fromResponse($message, $reply, $parts);

        return [
            'message' => $reply,
            'reply' => $reply,
            'parts' => $widgets,
            'widgets' => $widgets,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $history
     * @return array{0: string, 1: list<array<string, mixed>>}
     */
    private function generateUiResponse(string $message, string $systemPrompt, array $history): array
    {
        if (class_exists(GeneratorBridge::class)) {
            try {
                /** @var GeneratorBridge $bridge */
                $bridge = app(GeneratorBridge::class);
                $bridge->setChatbot(['instructions' => $systemPrompt]);

                $contextMessages = array_map(fn ($item) => [
                    'role' => $item['role'] ?? 'user',
                    'content' => $item['content'] ?? '',
                ], array_slice($history, -self::UI_CONTEXT_MESSAGE_LIMIT));

                return $this->parseGeneratedResponse(
                    $bridge->generate($message, $contextMessages),
                    $message
                );
            } catch (\Throwable $e) {
                Log::warning('TitanZeroService: GeneratorBridge failed for generate-ui.', ['error' => $e->getMessage()]);
            }
        }

        $apiKey = config('titan-chatbot.ai.openai_api_key', config('openai.api_key', env('OPENAI_API_KEY')));
        $model = config('titan-chatbot.ai.model', 'gpt-4o-mini');

        if ($apiKey) {
            try {
                $messages = [['role' => 'system', 'content' => $systemPrompt]];

                foreach (array_slice($history, -self::UI_CONTEXT_MESSAGE_LIMIT) as $item) {
                    $messages[] = [
                        'role' => $item['role'] ?? 'user',
                        'content' => $item['content'] ?? '',
                    ];
                }

                $messages[] = ['role' => 'user', 'content' => $message];

                $response = Http::withToken($apiKey)
                    ->timeout(15)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $model,
                        'messages' => $messages,
                        'temperature' => 0.4,
                    ]);

                if ($response->successful()) {
                    return $this->parseGeneratedResponse(
                        (string) $response->json('choices.0.message.content', ''),
                        $message
                    );
                }

                Log::warning('TitanZeroService: OpenAI generate-ui request failed.', ['status' => $response->status()]);
            } catch (\Throwable $e) {
                Log::warning('TitanZeroService: OpenAI generate-ui call threw.', ['error' => $e->getMessage()]);
            }
        }

        return $this->fallbackUiResponse($message);
    }

    /**
     * @return array{0: string, 1: list<array<string, mixed>>}
     */
    private function parseGeneratedResponse(string $raw, string $originalMessage): array
    {
        $raw = trim($raw);
        $raw = preg_replace('/^```(?:json)?\s*/i', '', $raw) ?? $raw;
        $raw = preg_replace('/\s*```$/', '', $raw) ?? $raw;

        $decoded = json_decode(trim($raw), true);

        if (is_array($decoded) && isset($decoded['message'])) {
            return [
                (string) ($decoded['message'] ?? $originalMessage),
                app(WidgetFactory::class)->normalize($decoded['parts'] ?? [], $originalMessage),
            ];
        }

        return [$raw !== '' ? $raw : 'Titan Zero is connected to the Business OS.', []];
    }

    /**
     * @return array{0: string, 1: list<array<string, mixed>>}
     */
    private function fallbackUiResponse(string $message): array
    {
        $reply = 'Titan Zero is connected to the Business OS. How can I help you today?';

        return [
            $reply,
            [app(WidgetFactory::class)->makeFromIntent($message !== '' ? $message : 'status', $reply)],
        ];
    }

    /**
     * @param  array<string, mixed>  $portalSnapshot
     */
    private function buildBusinessOsPrompt(string $appKey, string $page, array $portalSnapshot = []): string
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
      "kind": "<metric-card|line-chart|bar-chart|data-table|log-list|chat-thread|tool-call|settings-form|mcp-server-list|project-list>",
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

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
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

            if (is_string($snapshotJson) && strlen($snapshotJson) > self::MAX_PORTAL_SNAPSHOT_BYTES) {
                $snapshot['upcoming'] = array_slice($snapshot['upcoming'], 0, 3);
                $snapshot['invoices'] = array_slice($snapshot['invoices'], 0, 3);
                $snapshot['quotes'] = array_slice($snapshot['quotes'], 0, 3);

                Log::info('TitanZeroService: portal snapshot trimmed due to size', [
                    'customer_id' => $customerId,
                    'company_id' => $companyId,
                    'size' => strlen($snapshotJson),
                ]);
            }

            return $snapshot;
        } catch (\Throwable $e) {
            Log::warning('TitanZeroService: unable to build portal snapshot', ['error' => $e->getMessage()]);

            return [];
        }
    }
}
