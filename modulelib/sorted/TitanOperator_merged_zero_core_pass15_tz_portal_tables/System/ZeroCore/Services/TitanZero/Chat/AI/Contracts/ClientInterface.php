<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Contracts;

/**
 * TitanZeroChat AI Client Contract
 *
 * All AI provider adapters must implement this interface.
 * Consumed by TitanZeroChatService for provider-agnostic chat and embedding.
 */
interface ClientInterface
{
    /**
     * Send a chat completion request.
     *
     * @param  array  $messages  OpenAI-format message array [['role'=>'user','content'=>'...']]
     * @param  array  $options   Optional overrides: model, temperature, max_tokens, stream
     * @return array             ['ok'=>bool, 'content'=>string|null, 'usage'=>array|null, 'reason'=>string|null]
     */
    public function chat(array $messages, array $options = []): array;

    /**
     * Generate an embedding vector for the given input string.
     *
     * @param  string  $input    Text to embed
     * @param  array   $options  Optional overrides: model, dimensions
     * @return array             ['ok'=>bool, 'vector'=>float[]|null, 'reason'=>string|null]
     */
    public function embed(string $input, array $options = []): array;

    /**
     * Health check — confirms API key is present and provider is reachable.
     *
     * @return array ['ok'=>bool, 'provider'=>string, 'reason'=>string|null]
     */
    public function health(): array;
}
