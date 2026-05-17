<?php

namespace Modules\TitanEchoAssist\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Services\Generators\Contracts\GeneratorInterface;

class OpenAIGenerator implements GeneratorInterface
{
    public const ENDPOINT = 'https://api.openai.com/v1/chat/completions';

    private string $apiKey;
    private string $model;
    private int    $maxTokens;

    public function __construct()
    {
        $this->apiKey    = (string) $this->resolveApiKey();
        $this->model     = (string) config('titan-chatbot.ai.openai.model', 'gpt-4o-mini');
        $this->maxTokens = (int)    config('titan-chatbot.ai.openai.max_tokens', 4096);
    }

    public function generate(string $prompt, array $messages = [], array $tools = []): string
    {
        $payload = $this->buildPayload($prompt, $messages, $tools);

        $response = Http::withToken($this->apiKey)
            ->post(self::ENDPOINT, $payload);

        if ($response->failed()) {
            Log::error('OpenAIGenerator: request failed.', ['status' => $response->status()]);
            throw new \RuntimeException('OpenAI request failed with status ' . $response->status());
        }

        // If tools were used and provider returned a function call, extract the arguments JSON
        $choice = $response->json('choices.0') ?? [];
        if (!empty($tools) && isset($choice['message']['tool_calls'][0]['function']['arguments'])) {
            return $choice['message']['tool_calls'][0]['function']['arguments'];
        }

        return $response->json('choices.0.message.content', '');
    }

    public function generateWithUsage(string $prompt, array $messages = [], array $tools = []): array
    {
        $payload = $this->buildPayload($prompt, $messages, $tools);

        $response = Http::withToken($this->apiKey)
            ->post(self::ENDPOINT, $payload);

        if ($response->failed()) {
            Log::error('OpenAIGenerator: request failed.', ['status' => $response->status()]);
            throw new \RuntimeException('OpenAI request failed with status ' . $response->status());
        }

        $json = $response->json();

        return [
            'reply'             => $json['choices'][0]['message']['content'] ?? '',
            'prompt_tokens'     => $json['usage']['prompt_tokens'] ?? 0,
            'completion_tokens' => $json['usage']['completion_tokens'] ?? 0,
            'total_tokens'      => $json['usage']['total_tokens'] ?? 0,
        ];
    }

    public function getName(): string
    {
        return 'openai';
    }

    public function isAvailable(): bool
    {
        return $this->apiKey !== '';
    }

    private function buildPayload(string $prompt, array $messages, array $tools): array
    {
        $body = [
            'model'      => $this->model,
            'max_tokens' => $this->maxTokens,
            'messages'   => array_merge($messages, [['role' => 'user', 'content' => $prompt]]),
        ];

        if (!empty($tools)) {
            $body['tools']       = $tools;
            $body['tool_choice'] = 'auto';
        }

        return $body;
    }

    /**
     * Resolve the OpenAI API key, checking module config then the shared
     * openai config and finally the environment variable directly.
     */
    private function resolveApiKey(): string
    {
        return (string) (
            config('titan-chatbot.ai.openai.key')
            ?? config('openai.api_key')
            ?? env('OPENAI_API_KEY', '')
        );
    }
}
