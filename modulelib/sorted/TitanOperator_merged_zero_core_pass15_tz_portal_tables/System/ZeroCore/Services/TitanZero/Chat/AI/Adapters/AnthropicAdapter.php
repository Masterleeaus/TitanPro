<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Adapters;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Contracts\ClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TitanZeroChat — Anthropic (Claude) Adapter
 *
 * Handles chat completions via the Anthropic Messages API.
 * Note: Anthropic does not provide a public embedding endpoint.
 * Embed() falls back to OpenAI if OPENAI_API_KEY is available,
 * otherwise returns an error.
 */
class AnthropicAdapter implements ClientInterface
{
    protected string $apiKey;
    protected string $baseUrl  = 'https://api.anthropic.com/v1';
    protected string $model;
    protected string $version  = '2023-06-01';
    protected string $provider = 'anthropic';

    public function __construct(array $config = [])
    {
        $this->apiKey = $config['api_key'] ?? config('ai.providers.anthropic.api_key', env('ANTHROPIC_API_KEY', ''));
        $this->model  = $config['model']   ?? config('ai.providers.anthropic.model', 'claude-sonnet-4-6');
    }

    public function chat(array $messages, array $options = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing ANTHROPIC_API_KEY'];
        }

        // Separate system prompt from messages if present
        $system  = null;
        $filtered = [];
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $system = $msg['content'];
            } else {
                $filtered[] = $msg;
            }
        }

        $payload = [
            'model'      => $options['model']      ?? $this->model,
            'max_tokens' => $options['max_tokens']  ?? 2048,
            'messages'   => $filtered,
        ];
        if ($system) {
            $payload['system'] = $system;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key'         => $this->apiKey,
                'anthropic-version' => $this->version,
                'content-type'      => 'application/json',
            ])->timeout(60)->post("{$this->baseUrl}/messages", $payload);

            if (!$response->ok()) {
                Log::warning('TitanZeroChat AnthropicAdapter chat error', ['status' => $response->status(), 'body' => $response->body()]);
                return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $response->body()];
            }

            $json = $response->json();

            return [
                'ok'      => true,
                'content' => $json['content'][0]['text'] ?? null,
                'usage'   => $json['usage'] ?? null,
                'reason'  => null,
            ];
        } catch (\Throwable $e) {
            Log::error('TitanZeroChat AnthropicAdapter chat exception', ['error' => $e->getMessage()]);
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $e->getMessage()];
        }
    }

    public function embed(string $input, array $options = []): array
    {
        // Anthropic has no public embedding API — delegate to OpenAI if key available
        $openaiKey = env('OPENAI_API_KEY');
        if ($openaiKey) {
            return (new OpenAIAdapter(['api_key' => $openaiKey]))->embed($input, $options);
        }

        return [
            'ok'     => false,
            'vector' => null,
            'reason' => 'Anthropic does not support embeddings. Set OPENAI_API_KEY to use OpenAI embeddings as fallback.',
        ];
    }

    public function health(): array
    {
        return [
            'ok'       => !empty($this->apiKey),
            'provider' => $this->provider,
            'reason'   => empty($this->apiKey) ? 'Missing ANTHROPIC_API_KEY' : null,
        ];
    }
}
