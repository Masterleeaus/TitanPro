<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Billing\Usage\UsageRecord;
use Modules\TitanEchoAssist\Billing\Usage\UsageTracker;
use Modules\TitanEchoAssist\Events\AI\EngineError;
use Modules\TitanEchoAssist\Services\Generators\GeneratorFactory;
use Modules\TitanEchoAssist\Services\Generators\OpenAIGenerator;

class GeneratorBridge
{
    private mixed  $chatbot           = null;
    private mixed  $conversation      = null;
    private string $fakeResponse      = '';
    private array  $fallbackProviders = [];
    private string $fallbackMessage   = "Sorry, I can't answer right now.";

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

        $provider = config('titan-chatbot.ai.provider', 'openai');
        $messages = $this->buildMessages($context);

        // Try primary provider
        try {
            $generator = GeneratorFactory::make($provider);
            return $generator->generate($prompt, $messages);
        } catch (\Throwable $e) {
            Log::warning('GeneratorBridge: primary provider failed.', ['provider' => $provider, 'error' => $e->getMessage()]);
            Event::dispatch(new EngineError($e, static::class, ''));
        }

        // Try each fallback provider in sequence
        foreach ($this->fallbackProviders as $fallback) {
            try {
                $generator = GeneratorFactory::make($fallback);
                return $generator->generate($prompt, $messages);
            } catch (\Throwable $e) {
                Log::warning("GeneratorBridge: fallback provider '{$fallback}' failed.", ['error' => $e->getMessage()]);
                Event::dispatch(new EngineError($e, static::class, ''));
            }
        }

        return $this->fallbackMessage;
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

        $provider         = config('titan-chatbot.ai.provider', 'openai');
        $model            = $this->resolveProviderModel($provider);
        $messages         = $this->buildMessages($context);
        $reply            = $this->fallbackMessage;
        $promptTokens     = 0;
        $completionTokens = 0;
        $totalTokens      = 0;
        $usedProvider     = $provider;

        try {
            if ($provider === 'openai') {
                // OpenAI generator exposes usage data directly
                $generator = new OpenAIGenerator();
                $result    = $generator->generateWithUsage($prompt, $messages);
                $reply            = $result['reply'];
                $promptTokens     = $result['prompt_tokens'];
                $completionTokens = $result['completion_tokens'];
                $totalTokens      = $result['total_tokens'];
            } else {
                $generator    = GeneratorFactory::make($provider);
                $reply        = $generator->generate($prompt, $messages);
                $usedProvider = $provider;
            }
        } catch (\Throwable $e) {
            Log::warning('GeneratorBridge: primary provider failed in usage tracking.', ['error' => $e->getMessage()]);
            Event::dispatch(new EngineError($e, static::class, $sessionId));
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
        if (! $this->chatbot) {
            return null;
        }

        if (is_object($this->chatbot) && method_exists($this->chatbot, 'getAttribute')) {
            return $this->chatbot->getAttribute('instructions')
                ?? $this->chatbot->getAttribute('prompt')
                ?? null;
        }

        if (is_array($this->chatbot)) {
            return $this->chatbot['instructions'] ?? $this->chatbot['prompt'] ?? null;
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

