<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Contracts\ClientInterface;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Adapters\OpenAIAdapter;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\Adapters\AnthropicAdapter;
use InvalidArgumentException;

/**
 * TitanZeroChat AI Client Factory
 *
 * Resolves the correct AI provider adapter based on config or explicit provider name.
 * Used throughout TitanZeroChat to keep controllers provider-agnostic.
 *
 * Usage:
 *   $client = AIClientFactory::make();               // uses default provider from config
 *   $client = AIClientFactory::make('anthropic');     // explicit provider
 *   $client = AIClientFactory::make('openai', [...]); // with config overrides
 */
class AIClientFactory
{
    /**
     * Resolve an AI client adapter.
     *
     * @param  string|null  $provider  'openai' | 'anthropic' | null (uses config default)
     * @param  array        $config    Optional config overrides (api_key, model, etc.)
     */
    public static function make(?string $provider = null, array $config = []): ClientInterface
    {
        $provider = $provider ?? config('titan_operator.zero-chat.ai.default_provider', 'openai');

        return match (strtolower($provider)) {
            'openai'    => new OpenAIAdapter($config),
            'anthropic' => new AnthropicAdapter($config),
            default     => throw new InvalidArgumentException("TitanZeroChat: Unknown AI provider [{$provider}]. Supported: openai, anthropic."),
        };
    }

    /**
     * Make a client for embedding tasks specifically.
     * Always returns OpenAI adapter since it has the most reliable embedding support.
     */
    public static function embedder(array $config = []): ClientInterface
    {
        return new OpenAIAdapter(array_merge([
            'api_key'     => env('OPENAI_API_KEY'),
            'embed_model' => config('titan_operator.zero-chat.webchat.embedding_model', 'text-embedding-ada-002'),
        ], $config));
    }

    /**
     * Health check all registered providers.
     *
     * @return array ['openai' => ['ok'=>bool, ...], 'anthropic' => ['ok'=>bool, ...]]
     */
    public static function healthAll(): array
    {
        return [
            'openai'    => (new OpenAIAdapter())->health(),
            'anthropic' => (new AnthropicAdapter())->health(),
        ];
    }
}
