<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Billing\Usage\UsageRecord;
use Modules\TitanEchoAssist\Billing\Usage\UsageTracker;
use Modules\TitanEchoAssist\Events\AI\EngineError;
use Modules\TitanEchoAssist\Services\KnowledgeRetriever;
use Modules\TitanEchoAssist\Services\Generators\AnthropicGenerator;
use Modules\TitanEchoAssist\Services\Generators\GeneratorFactory;
use Modules\TitanEchoAssist\Services\Generators\OpenAIGenerator;

class GeneratorBridge
{
    private mixed  $chatbot           = null;
    private mixed  $conversation      = null;
    private string $fakeResponse      = '';
    private array  $fallbackProviders = [];
    private ?string $ragContext       = null;

    public function fake(string $response = ''): self
    {
        $this->fakeResponse = $response !== '' ? $response : 'This is a fake AI response for testing.';
        return $this;
    }

    public function withFallbackProviders(array $providers): self
    {
        $this->fallbackProviders = $providers;
        return $this;
    }

    public function generate(string $prompt, array $context = []): string
    {
        if (!empty($this->fakeResponse)) {
            return $this->fakeResponse;
        }

        $this->ragContext = $this->buildRagContext($prompt);

        $provider = config('titan-chatbot.ai.provider', 'openai');
        $messages = $this->buildMessages($context);
        $errors = [];

        // Try primary provider
        try {
            $generator = match ($provider) {
                'anthropic' => new AnthropicGenerator(),
                default => GeneratorFactory::make($provider),
            };

            return $generator->generate($prompt, $messages);
        } catch (\Throwable $e) {
            $errors[] = ['provider' => $provider, 'error' => $e->getMessage()];
            Log::warning('GeneratorBridge: primary provider failed.', ['provider' => $provider, 'error' => $e->getMessage()]);
            Event::dispatch(new EngineError($e, static::class, ''));
        }

        // Try each fallback provider in sequence
        foreach ($this->fallbackProviders as $fallback) {
            try {
                $generator = GeneratorFactory::make($fallback);
                return $generator->generate($prompt, $messages);
            } catch (\Throwable $e) {
                $errors[] = ['provider' => $fallback, 'error' => $e->getMessage()];
                Log::warning("GeneratorBridge: fallback provider '{$fallback}' failed.", ['error' => $e->getMessage()]);
                Event::dispatch(new EngineError($e, static::class, ''));
            }
        }

        return json_encode([
            'ok' => false,
            'reason' => 'ai_provider_failure',
            'errors' => $errors,
        ], JSON_UNESCAPED_SLASHES);
    }

    public function generateWithUsageTracking(
        string $prompt,
        array  $context    = [],
        string $sessionId  = '',
        int    $chatbotId  = 0,
        int    $tenantId   = 0
    ): array {
        if (!empty($this->fakeResponse)) {
            $reply  = $this->fakeResponse;
            $usage  = new UsageRecord(
                provider:  'fake',
                sessionId: $sessionId,
                chatbotId: $chatbotId,
                tenantId:  $tenantId,
            );
            return ['reply' => $reply, 'usage' => $usage->normalize()];
        }

        $this->ragContext = $this->buildRagContext($prompt);

        $provider         = config('titan-chatbot.ai.provider', 'openai');
        $model            = $this->resolveProviderModel($provider);
        $messages         = $this->buildMessages($context);
        $reply            = '';
        $promptTokens     = 0;
        $completionTokens = 0;
        $totalTokens      = 0;
        $usedProvider     = $provider;
        $errors           = [];

        try {
            switch ($provider) {
                case 'openai':
                    $generator = new OpenAIGenerator();
                    $result    = $generator->generateWithUsage($prompt, $messages);
                    $reply            = $result['reply'];
                    $promptTokens     = $result['prompt_tokens'];
                    $completionTokens = $result['completion_tokens'];
                    $totalTokens      = $result['total_tokens'];
                    break;
                case 'anthropic':
                    $generator    = new AnthropicGenerator();
                    $reply        = $generator->generate($prompt, $messages);
                    $usedProvider = 'anthropic';
                    break;
                default:
                    $generator    = GeneratorFactory::make($provider);
                    $reply        = $generator->generate($prompt, $messages);
                    $usedProvider = $provider;
                    break;
            }
        } catch (\Throwable $e) {
            $errors[] = ['provider' => $provider, 'error' => $e->getMessage()];
            Log::warning('GeneratorBridge: primary provider failed in usage tracking.', ['error' => $e->getMessage()]);
            Event::dispatch(new EngineError($e, static::class, $sessionId));
        }

        if ($reply === '') {
            foreach ($this->fallbackProviders as $fallback) {
                try {
                    $generator = GeneratorFactory::make($fallback);
                    $reply = $generator->generate($prompt, $messages);
                    $usedProvider = $fallback;
                    break;
                } catch (\Throwable $e) {
                    $errors[] = ['provider' => $fallback, 'error' => $e->getMessage()];
                    Log::warning("GeneratorBridge: fallback provider '{$fallback}' failed in usage tracking.", ['error' => $e->getMessage()]);
                    Event::dispatch(new EngineError($e, static::class, $sessionId));
                }
            }
        }

        if ($reply === '') {
            $reply = json_encode([
                'ok' => false,
                'reason' => 'ai_provider_failure',
                'errors' => $errors,
            ], JSON_UNESCAPED_SLASHES);
        }

        $usage = new UsageRecord(
            provider:         $usedProvider,
            model:            $model,
            promptTokens:     $promptTokens,
            completionTokens: $completionTokens,
            totalTokens:      $totalTokens,
            tenantId:         $tenantId,
            chatbotId:        $chatbotId,
            sessionId:        $sessionId,
        );

        $normalized = $usage->normalize();

        app(UsageTracker::class)->record($normalized);

        return ['reply' => $reply, 'usage' => $normalized];
    }

    public function setChatbot(mixed $chatbot): self
    {
        $this->chatbot = $chatbot;

        return $this;
    }

    public function setConversation(mixed $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Build the messages array including a system prompt (if any) followed
     * by the provided context messages.
     */
    private function buildMessages(array $context): array
    {
        $messages = [];

        if ($systemPrompt = $this->buildSystemPrompt()) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }

        foreach ($context as $ctx) {
            $messages[] = $ctx;
        }

        return $messages;
    }

    private function buildSystemPrompt(): ?string
    {
        $basePrompt = null;

        if ($this->chatbot) {
            if (is_object($this->chatbot) && method_exists($this->chatbot, 'getAttribute')) {
                $basePrompt = $this->chatbot->getAttribute('instructions')
                    ?? $this->chatbot->getAttribute('prompt')
                    ?? null;
            } elseif (is_array($this->chatbot)) {
                $basePrompt = $this->chatbot['instructions'] ?? $this->chatbot['prompt'] ?? null;
            }
        }

        if ($this->ragContext === null || $this->ragContext === '') {
            return $basePrompt;
        }

        $contextPrompt = "Knowledge base context:\n{$this->ragContext}";

        return trim(($basePrompt ? "{$basePrompt}\n\n" : '') . $contextPrompt);
    }

    private function buildRagContext(string $prompt): ?string
    {
        $chatbotId = $this->resolveChatbotId();

        if ($chatbotId === null) {
            return null;
        }

        try {
            $chunks = app(KnowledgeRetriever::class)->retrieve(
                query: $prompt,
                chatbotId: (string) $chatbotId,
                topK: (int) config('titan-chatbot.ai.rag_chunks_limit', 5),
            );

            if ($chunks === []) {
                return null;
            }

            return implode("\n\n", array_column($chunks, 'content'));
        } catch (\Throwable $e) {
            Log::warning('GeneratorBridge: failed to retrieve RAG context.', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function resolveChatbotId(): ?int
    {
        if (! $this->chatbot) {
            return null;
        }

        if (is_object($this->chatbot) && method_exists($this->chatbot, 'getKey')) {
            return (int) $this->chatbot->getKey();
        }

        if (is_object($this->chatbot) && method_exists($this->chatbot, 'getAttribute')) {
            $id = $this->chatbot->getAttribute('id');

            return $id !== null ? (int) $id : null;
        }

        if (is_array($this->chatbot) && isset($this->chatbot['id'])) {
            return (int) $this->chatbot['id'];
        }

        return null;
    }

    /**
     * Resolve the active model name for the given provider.
     * Checks the per-provider config block first, then falls back to the
     * shared legacy `titan-chatbot.ai.model` key.
     */
    private function resolveProviderModel(string $provider): string
    {
        return (string) (
            config("titan-chatbot.ai.{$provider}.model")
            ?? config('titan-chatbot.ai.model', 'gpt-4o-mini')
        );
    }
}
