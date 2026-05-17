<?php

namespace Modules\TitanCore\AI\Adapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\TitanCore\AI\ClientInterface;
use Modules\TitanCore\Services\UsageLogger;

class AnthropicClient implements ClientInterface
{
    protected const ENDPOINT         = 'https://api.anthropic.com/v1/messages';
    protected const ANTHROPIC_VERSION = '2023-06-01';

    protected string $apiKey;
    protected string $model;
    protected string $provider = 'anthropic';

    public function __construct()
    {
        $this->apiKey = config('ai.providers.anthropic.api_key') ?? env('ANTHROPIC_API_KEY', '');
        $this->model  = config('ai.providers.anthropic.model', 'claude-3-haiku-20240307');
    }

    public function chat(array $messages, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing ANTHROPIC_API_KEY'];
        }

        // Separate system prompt from conversation messages (Anthropic-specific format)
        $system   = null;
        $filtered = [];
        foreach ($messages as $msg) {
            if (($msg['role'] ?? '') === 'system') {
                $system = $msg['content'];
            } else {
                $filtered[] = $msg;
            }
        }

        $maxTokens = $opts['max_tokens'] ?? 4096;
        $model     = $opts['model'] ?? $this->model;

        $payload = [
            'model'      => $model,
            'max_tokens' => $maxTokens,
            'messages'   => $filtered,
        ];

        if ($system !== null) {
            $payload['system'] = $system;
        }

        if (!empty($opts['tools'])) {
            $payload['tools'] = $opts['tools'];
        }

        try {
            $response = Http::withHeaders([
                'x-api-key'         => $this->apiKey,
                'anthropic-version' => self::ANTHROPIC_VERSION,
                'Content-Type'      => 'application/json',
                'Accept'            => 'application/json',
            ])->post(self::ENDPOINT, $payload);

            if ($response->failed()) {
                Log::error('AnthropicClient: request failed.', ['status' => $response->status()]);
                return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'HTTP ' . $response->status()];
            }

            $json = $response->json();

            // Extract first text content block
            $content = null;
            foreach ($json['content'] ?? [] as $block) {
                if (($block['type'] ?? '') === 'text') {
                    $content = $block['text'];
                    break;
                }
            }

            $key = optional(auth()->user())->tenant_id ? ('tenant:' . auth()->user()->tenant_id) : 'global';
            $inputTokens  = $json['usage']['input_tokens'] ?? 0;
            $outputTokens = $json['usage']['output_tokens'] ?? 0;
            UsageLogger::add($key, $inputTokens + $outputTokens, 1);

            return [
                'ok'      => true,
                'content' => $content,
                'usage'   => [
                    'prompt_tokens'     => $inputTokens,
                    'completion_tokens' => $outputTokens,
                ],
                'reason'  => null,
            ];
        } catch (\Throwable $e) {
            Log::error('AnthropicClient: exception during chat.', ['error' => $e->getMessage()]);
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $e->getMessage()];
        }
    }

    public function embed(array $input, array $opts = []): array
    {
        // Anthropic does not provide a public embeddings API
        return ['ok' => false, 'vector' => null, 'reason' => 'Anthropic does not support embeddings'];
    }

    public function health(): array
    {
        return ['ok' => (bool)$this->apiKey, 'provider' => $this->provider, 'reason' => $this->apiKey ? null : 'Missing API key'];
    }
}

