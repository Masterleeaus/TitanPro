<?php

namespace Modules\TitanEchoAssist\Services\Generators\Contracts;

interface GeneratorInterface
{
    /**
     * Generate a reply from the AI provider.
     *
     * @param  string  $prompt   The user prompt / question.
     * @param  array   $messages Prior conversation messages (role/content pairs).
     * @param  array   $tools    Optional tool/function definitions.
     * @return string            The generated text response.
     */
    public function generate(string $prompt, array $messages = [], array $tools = []): string;

    /**
     * Human-readable provider name (e.g. "openai", "anthropic", "gemini").
     */
    public function getName(): string;

    /**
     * Whether this generator is configured and ready to use.
     */
    public function isAvailable(): bool;
}
