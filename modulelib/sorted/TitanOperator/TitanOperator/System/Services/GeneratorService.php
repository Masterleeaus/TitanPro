<?php

namespace App\Extensions\TitanOperator\System\Services;

use App\Domains\Engine\Enums\EngineEnum;
use App\Domains\Entity\Enums\EntityEnum;
use App\Domains\Entity\Facades\Entity;
use App\Extensions\TitanOperator\System\Generators\AnthropicGenerator;
use App\Extensions\TitanOperator\System\Generators\Contracts\Generator;
use App\Extensions\TitanOperator\System\Generators\GeminiGenerator;
use App\Extensions\TitanOperator\System\Generators\OpenAIGenerator;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GeneratorService
{
    public string $prompt;

    public TitanOperator $titan_operator;

    public TitanOperatorConversation $conversation;

    public EntityEnum $entityEnum;

    public function generate(): string
    {
        // Per-titan_operator rate limit (limit_per_minute).
        $limit = (int) ($this->titan_operator->getAttribute('limit_per_minute') ?? 0);
        if ($limit > 0) {
            $scope = (string) ($this->conversation->getAttribute('session_id') ?? 'global');
            $key = sprintf('titan_operator:%d:rl:%s:%s', (int) $this->titan_operator->getKey(), date('YmdHi'), $scope);
            $count = (int) Cache::get($key, 0);
            if ($count >= $limit) {
                return trans('Too many messages. Please wait a moment and try again.');
            }
            Cache::put($key, $count + 1, now()->addMinutes(2));
        }

        // External provider mode (optional)
        if (($this->titan_operator->getAttribute('provider_type') ?? null) === 'external' && $this->titan_operator->getAttribute('external_endpoint_url')) {
            return $this->generateExternal();
        }

        $generator = $this->generator();

        $driver = Entity::driver($this->entityEnum)
            ->forUser($this->titan_operator->user);

        if (! $driver->hasCreditBalance()) {
            return trans('You have no credits left. Please consider upgrading your plan.');
        }

        $generated = $generator
            ->setConversation($this->conversation)
            ->setTitanOperator($this->titan_operator)
            ->setEntity($this->entityEnum)
            ->setPrompt($this->prompt)
            ->generate();

        $driver
            ->input($generated)
            ->calculateCredit()
            ->decreaseCredit();

        return $generated;
    }


    protected function generateExternal(): string
    {
        $url = (string) $this->titan_operator->getAttribute('external_endpoint_url');
        $timeoutMs = (int) ($this->titan_operator->getAttribute('external_timeout_ms') ?? 15000);
        $authType = (string) ($this->titan_operator->getAttribute('external_auth_type') ?? 'bearer');
        $authToken = (string) ($this->titan_operator->getAttribute('external_auth_token') ?? '');
        $signingSecret = (string) ($this->titan_operator->getAttribute('external_signing_secret') ?? '');

                $recent = $this->conversation->histories()->orderByDesc('id')->limit(20)->get()->reverse()->values()->map(function ($h) {
            return [
                'id' => $h->getAttribute('id'),
                'role' => $h->getAttribute('role'),
                'message' => $h->getAttribute('message'),
                'type' => $h->getAttribute('type'),
                'message_type' => $h->getAttribute('message_type'),
                'content_type' => $h->getAttribute('content_type'),
                'media_url' => $h->getAttribute('media_url'),
                'media_name' => $h->getAttribute('media_name'),
                'created_at' => optional($h->getAttribute('created_at'))->toIso8601String(),
            ];
        })->all();

        $payload = [
            'operator_id'       => $this->titan_operator->getAttribute('id'),
            'operator_uuid'     => $this->titan_operator->getAttribute('uuid'),
            'conversation_id'  => $this->conversation->getAttribute('id'),
            'session_id'       => $this->conversation->getAttribute('session_id'),
            'channel'          => $this->conversation->getAttribute('operator_channel') ?? 'frame',
            'customer'         => $this->conversation->customer ? [
                'id' => $this->conversation->customer->getKey(),
                'name' => $this->conversation->customer->getAttribute('name'),
                'email' => $this->conversation->customer->getAttribute('email'),
                'phone' => $this->conversation->customer->getAttribute('phone'),
                'payload' => $this->conversation->customer->getAttribute('payload'),
            ] : null,
            'instructions'     => $this->titan_operator->getAttribute('instructions'),
            'strict_instructions' => (bool) $this->titan_operator->getAttribute('do_not_go_beyond_instructions'),
            'prompt'           => $this->prompt,
            'recent_messages'  => $recent,
        ];

        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';

        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'X-Request-Id' => (string) Str::uuid(),
        ];

        if ($authToken !== '') {
            if (strtolower($authType) === 'header') {
                $headers['X-External-Auth'] = $authToken;
            } else {
                $headers['Authorization'] = 'Bearer ' . $authToken;
            }
        }

        if ($signingSecret !== '') {
            $headers['X-Signature'] = hash_hmac('sha256', $body, $signingSecret);
        }

        $resp = Http::withHeaders($headers)
            ->timeout(max(1, (int) ceil($timeoutMs / 1000)))
            ->withBody($body, 'application/json')
            ->post($url);

        if (! $resp->ok()) {
            return trans("Sorry, I can't answer right now.");
        }

        $json = $resp->json();

        // Expected: { reply: "..." } or { message: "..." }
        $reply = (string) (data_get($json, 'reply') ?? data_get($json, 'message') ?? '');

        return $reply !== '' ? $reply : trans("Sorry, I can't answer right now.");
    }

    public function generator(): Generator
    {
        $setting = Setting::getCache();

        // TODO: titan_operator model default openai_default_model
        //		$model = $this->titan_operator->getAttribute('ai_model');
        $model = $setting->openai_default_model;

        $this->entityEnum = EntityEnum::fromSlug($setting->openai_default_model);

        $engine = $this->entityEnum->engine();

        return match ($engine) {
            EngineEnum::GEMINI    => app(GeminiGenerator::class),
            EngineEnum::ANTHROPIC => app(AnthropicGenerator::class),
            default               => app(OpenAIGenerator::class),
        };
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getTitanOperator(): TitanOperator
    {
        return $this->titan_operator;
    }

    public function setTitanOperator(TitanOperator $titan_operator): static
    {
        $this->titan_operator = $titan_operator;

        return $this;
    }

    public function getConversation(): TitanOperatorConversation
    {
        return $this->conversation;
    }

    public function setConversation(TitanOperatorConversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }
}
