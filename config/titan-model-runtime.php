<?php

/**
 * Titan Model Runtime — Provider Adapters, Model Routing, Failover, Timeouts
 *
 * This file contains the low-level connection details for every AI provider
 * the platform can talk to. High-level AI settings (features, agents, guardrails)
 * live in config/titan-ai.php.
 *
 * Required keys validated by TitanCoreServiceProvider::boot():
 *   - titan-model-runtime.providers  (must be a non-empty array)
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Provider Adapters
    |--------------------------------------------------------------------------
    |
    | Configure every AI provider the platform may use. Only providers listed
    | here can be selected as the default in config/titan-ai.php.
    |
    */

    'providers' => [

        'openai' => [
            'enabled' => env('TITAN_OPENAI_ENABLED', true),
            'api_key' => env('OPENAI_API_KEY'),
            'base'    => env('OPENAI_BASE', 'https://api.openai.com'),
            'model'   => env('OPENAI_MODEL', 'gpt-4o-mini'),
        ],

        'anthropic' => [
            'enabled' => env('TITAN_ANTHROPIC_ENABLED', false),
            'api_key' => env('ANTHROPIC_API_KEY'),
            'base'    => env('ANTHROPIC_BASE', 'https://api.anthropic.com'),
            'model'   => env('ANTHROPIC_MODEL', 'claude-3-haiku'),
        ],

        'titanai' => [
            'enabled'               => env('TITAN_TITANAI_ENABLED', false),
            'base_url'              => rtrim(env('TITAN_TITANAI_BASE_URL', ''), '/') ?: null,
            'api_key'               => env('TITAN_TITANAI_API_KEY', ''),
            'allowed_path_prefixes' => ['/api', '/v1', '/v2'],
            'timeout_seconds'       => (int) env('TITAN_TITANAI_TIMEOUT', 60),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Model Routing
    |--------------------------------------------------------------------------
    |
    | Rules that override the default provider for specific task types.
    | Each entry maps a task key to a provider and optional model override.
    |
    | Example:
    |   'embeddings' => ['provider' => 'openai', 'model' => 'text-embedding-3-small'],
    |   'vision'     => ['provider' => 'openai', 'model' => 'gpt-4o'],
    |
    */

    'routing' => [
        'embeddings' => [
            'provider' => env('TITAN_ROUTING_EMBEDDINGS_PROVIDER', 'openai'),
            'model'    => env('TITAN_ROUTING_EMBEDDINGS_MODEL', 'text-embedding-3-small'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Failover
    |--------------------------------------------------------------------------
    |
    | When a provider returns a retryable error, Titan will try each fallback
    | provider in order before giving up.
    |
    */

    'failover' => [
        'enabled'   => env('TITAN_FAILOVER_ENABLED', false),
        'providers' => array_filter(
            explode(',', env('TITAN_FAILOVER_CHAIN', 'openai,anthropic')),
            fn (string $p) => $p !== ''
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Timeouts
    |--------------------------------------------------------------------------
    |
    | Default HTTP timeouts (in seconds) applied to all provider requests
    | unless overridden per-provider above.
    |
    */

    'timeouts' => [
        'connect' => (int) env('TITAN_TIMEOUT_CONNECT', 10),
        'request' => (int) env('TITAN_TIMEOUT_REQUEST', 60),
        'stream'  => (int) env('TITAN_TIMEOUT_STREAM', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Pricing (USD per 1 000 tokens)
    |--------------------------------------------------------------------------
    |
    | Keep pricing here so it can be updated without code changes.
    | Unknown models default to null cost but still log token counts.
    |
    */

    'pricing' => [
        // 'gpt-4o-mini'            => ['prompt_per_1k' => 0.0, 'completion_per_1k' => 0.0],
        // 'text-embedding-3-small' => ['embedding_per_1k' => 0.0],
    ],

    /*
    |--------------------------------------------------------------------------
    | Metrics
    |--------------------------------------------------------------------------
    |
    | Controls runtime telemetry for model usage (token counts, latency, cost).
    |
    */

    'metrics' => [
        'enabled'      => env('TITAN_METRICS_ENABLED', true),
        'access_token' => env('TITAN_METRICS_TOKEN', null),
        'ttl_seconds'  => (int) env('TITAN_METRICS_TTL', 600),
    ],

];
