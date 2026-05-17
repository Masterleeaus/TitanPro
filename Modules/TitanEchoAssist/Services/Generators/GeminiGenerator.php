<?php

namespace Modules\TitanEchoAssist\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Services\Generators\Contracts\GeneratorInterface;

/**
 * Google Gemini generator adapter for TitanEchoAssist.
 *
 * Uses the Gemini `generateContent` REST endpoint.  Supports a system
 * instruction and extracts function calls from the response when tools are
 * provided.
 */
class GeminiGenerator implements GeneratorInterface
{
    public const ENDPOINT_TEMPLATE = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';

    private string $apiKey;
    private string $model;
    private int    $maxTokens;

    public function __construct()
    {
        $this->apiKey    = (string) config('titan-chatbot.ai.gemini.key', env('GEMINI_API_KEY', ''));
        $this->model     = (string) config('titan-chatbot.ai.gemini.model', 'gemini-pro');
        $this->maxTokens = (int)    config('titan-chatbot.ai.gemini.max_tokens', 4096);
    }

    public function generate(string $prompt, array $messages = [], array $tools = []): string
    {
        $endpoint = sprintf(self::ENDPOINT_TEMPLATE, $this->model);

        $payload = $this->buildPayload($prompt, $messages, $tools);

        $response = Http::withQueryParameters(['key' => $this->apiKey])
            ->post($endpoint, $payload);

        if ($response->failed()) {
            Log::error('GeminiGenerator: request failed.', ['status' => $response->status()]);
            throw new \RuntimeException('Gemini request failed with status ' . $response->status());
        }

        $json = $response->json();

        // Extract function call if tools were used
        $parts = $json['candidates'][0]['content']['parts'] ?? [];
        foreach ($parts as $part) {
            if (isset($part['functionCall'])) {
                return json_encode($part['functionCall']['args'] ?? []);
            }
        }

        // Return plain text
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }

    public function getName(): string
    {
        return 'gemini';
    }

    public function isAvailable(): bool
    {
        return $this->apiKey !== '';
    }

    private function buildPayload(string $prompt, array $messages, array $tools): array
    {
        $contents        = [];
        $systemInstruction = null;

        // Map conversation history to Gemini's `contents` format
        foreach ($messages as $msg) {
            $role = $msg['role'] ?? 'user';

            if ($role === 'system') {
                // Gemini uses a top-level systemInstruction instead of a system role
                $systemInstruction = ['parts' => [['text' => (string) ($msg['content'] ?? '')]]];
                continue;
            }

            // Gemini uses "model" instead of "assistant"
            $contents[] = [
                'role'  => $role === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => (string) ($msg['content'] ?? '')]],
            ];
        }

        // Append the current user prompt
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $prompt]],
        ];

        $payload = [
            'contents'         => $contents,
            'generationConfig' => ['maxOutputTokens' => $this->maxTokens],
        ];

        if ($systemInstruction !== null) {
            $payload['systemInstruction'] = $systemInstruction;
        }

        if (!empty($tools)) {
            $payload['tools'] = [['function_declarations' => $this->convertTools($tools)]];
        }

        return $payload;
    }

    /**
     * Convert OpenAI-style tool definitions to the Gemini function declaration format.
     */
    private function convertTools(array $tools): array
    {
        $declarations = [];

        foreach ($tools as $tool) {
            if (($tool['type'] ?? '') === 'function' && isset($tool['function'])) {
                $fn = $tool['function'];
                $declarations[] = [
                    'name'        => $fn['name'] ?? '',
                    'description' => $fn['description'] ?? '',
                    'parameters'  => $fn['parameters'] ?? ['type' => 'object', 'properties' => []],
                ];
            }
        }

        return $declarations;
    }
}
