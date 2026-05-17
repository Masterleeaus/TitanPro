<?php

namespace Modules\TitanEchoAssist\Services\Generators;

use Modules\TitanEchoAssist\Services\Generators\Contracts\GeneratorInterface;

class GeneratorFactory
{
    /**
     * Build a generator instance for the given provider name.
     *
     * @param  string  $provider  One of "openai", "anthropic", "gemini".
     * @return GeneratorInterface
     *
     * @throws \InvalidArgumentException When an unknown provider is requested.
     */
    public static function make(string $provider): GeneratorInterface
    {
        return match ($provider) {
            'openai'    => new OpenAIGenerator(),
            'anthropic' => new AnthropicGenerator(),
            'gemini'    => new GeminiGenerator(),
            default     => throw new \InvalidArgumentException("Unknown AI provider: {$provider}"),
        };
    }
}
