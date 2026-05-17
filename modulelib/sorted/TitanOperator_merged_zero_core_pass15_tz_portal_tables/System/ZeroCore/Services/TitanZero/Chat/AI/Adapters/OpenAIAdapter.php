<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Adapters;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Contracts\ClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TitanZeroChat — OpenAI Adapter
 *
 * Handles chat completions and text embeddings via the OpenAI API.
 * Supports custom base_url for Azure OpenAI or compatible endpoints.
 */
class OpenAIAdapter implements ClientInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $chatModel;
    protected string $embedModel;
    protected string $provider = 'openai';

    public function __construct(array $config = [])
    {
        $this->apiKey    = $config['api_key']    ?? config('ai.providers.openai.api_key', env('OPENAI_API_KEY', ''));
        $this->baseUrl   = rtrim($config['base_url'] ?? 'https://api.openai.com/v1', '/');
        $this->chatModel = $config['chat_model'] ?? config('titan_operator.zero-chat.webchat.chat_model', 'gpt-4o-mini');
        $this->embedModel= $config['embed_model'] ?? config('titan_operator.zero-chat.webchat.embedding_model', 'text-embedding-ada-002');
    }

    public function chat(array $messages, array $options = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing OPENAI_API_KEY'];
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model'       => $options['model']       ?? $this->chatModel,
                    'messages'    => $messages,
                    'temperature' => $options['temperature'] ?? 0.7,
                    'max_tokens'  => $options['max_tokens']  ?? 2048,
                    'stream'      => $options['stream']      ?? false,
                ]);

            if (!$response->ok()) {
                Log::warning('TitanZeroChat OpenAIAdapter chat error', ['status' => $response->status(), 'body' => $response->body()]);
                return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $response->body()];
            }

            $json = $response->json();

            return [
                'ok'      => true,
                'content' => $json['choices'][0]['message']['content'] ?? null,
                'usage'   => $json['usage'] ?? null,
                'reason'  => null,
            ];
        } catch (\Throwable $e) {
            Log::error('TitanZeroChat OpenAIAdapter chat exception', ['error' => $e->getMessage()]);
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $e->getMessage()];
        }
    }

    public function embed(string $input, array $options = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'vector' => null, 'reason' => 'Missing OPENAI_API_KEY'];
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/embeddings", [
                    'model' => $options['model'] ?? $this->embedModel,
                    'input' => $input,
                ]);

            if (!$response->ok()) {
                Log::warning('TitanZeroChat OpenAIAdapter embed error', ['status' => $response->status()]);
                return ['ok' => false, 'vector' => null, 'reason' => $response->body()];
            }

            $json = $response->json();

            return [
                'ok'     => true,
                'vector' => $json['data'][0]['embedding'] ?? null,
                'reason' => null,
            ];
        } catch (\Throwable $e) {
            Log::error('TitanZeroChat OpenAIAdapter embed exception', ['error' => $e->getMessage()]);
            return ['ok' => false, 'vector' => null, 'reason' => $e->getMessage()];
        }
    }

    public function health(): array
    {
        return [
            'ok'       => !empty($this->apiKey),
            'provider' => $this->provider,
            'reason'   => empty($this->apiKey) ? 'Missing OPENAI_API_KEY' : null,
        ];
    }
}
