<?php

namespace Modules\TitanEchoAssist\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Services\Generators\Contracts\GeneratorInterface;

/**
 * Anthropic (Claude) generator adapter for TitanEchoAssist.
 *
 * Calls the Anthropic Messages API.  System messages are extracted from the
 * conversation history and forwarded via the dedicated `system` parameter
 * (Anthropic does not accept system role inside the messages array).
 */
class AnthropicGenerator implements GeneratorInterface
{
    public const ENDPOINT         = 'https://api.anthropic.com/v1/messages';
    public const ANTHROPIC_VERSION = '2023-06-01';

    private string $apiKey;
    private string $model;
    private int    $maxTokens;

    public function __construct()
    {
        $this->apiKey    = (string) config('titan-chatbot.ai.anthropic.key', env('ANTHROPIC_API_KEY', ''));
        $this->model     = (string) config('titan-chatbot.ai.anthropic.model', 'claude-3-haiku-20240307');
        $this->maxTokens = (int)    config('titan-chatbot.ai.anthropic.max_tokens', 4096);
    }

    public function generate(string $prompt, array $messages = [], array $tools = []): string
    {
        [$system, $history] = $this->extractSystemMessage($messages);

        $history[] = ['role' => 'user', 'content' => $prompt];

        $payload = [
            'model'      => $this->model,
            'max_tokens' => $this->maxTokens,
            'messages'   => $history,
        ];

        if ($system !== null) {
            $payload['system'] = $system;
        }

        if (!empty($tools)) {
            $payload['tools'] = $this->convertTools($tools);
        }

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => self::ANTHROPIC_VERSION,
            'Content-Type'      => 'application/json',
            'Accept'            => 'application/json',
        ])->post(self::ENDPOINT, $payload);

        if ($response->failed()) {
            Log::error('AnthropicGenerator: request failed.', ['status' => $response->status()]);
            throw new \RuntimeException('Anthropic request failed with status ' . $response->status());
        }

        $json = $response->json();

        // If the model issued a tool_use block, return the JSON-encoded input
        foreach ($json['content'] ?? [] as $block) {
            if (($block['type'] ?? '') === 'tool_use') {
                return json_encode($block['input'] ?? []);
            }
        }

        // Return the first text block
        foreach ($json['content'] ?? [] as $block) {
            if (($block['type'] ?? '') === 'text') {
                return $block['text'];
            }
        }

        return '';
    }

    public function getName(): string
    {
        return 'anthropic';
    }

    public function isAvailable(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Separate system messages from the conversation history.
     * Anthropic requires the system prompt to be sent as a top-level `system`
     * string, not as a message with role "system".
     *
     * @return array{0: string|null, 1: array}
     */
    private function extractSystemMessage(array $messages): array
    {
        $system  = null;
        $history = [];

        foreach ($messages as $msg) {
            if (($msg['role'] ?? '') === 'system') {
                $system = (string) ($msg['content'] ?? '');
            } else {
                $history[] = $msg;
            }
        }

        return [$system, $history];
    }

    /**
     * Convert OpenAI-style tool definitions to the Anthropic format.
     */
    private function convertTools(array $tools): array
    {
        $converted = [];

        foreach ($tools as $tool) {
            if (($tool['type'] ?? '') === 'function' && isset($tool['function'])) {
                $fn = $tool['function'];
                $converted[] = [
                    'name'         => $fn['name'] ?? '',
                    'description'  => $fn['description'] ?? '',
                    'input_schema' => $fn['parameters'] ?? ['type' => 'object', 'properties' => []],
                ];
            }
        }

        return $converted;
    }
}
