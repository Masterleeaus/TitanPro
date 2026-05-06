<?php

/**
 * Verifies that the three Titan platform config files load correctly and that
 * all required keys are present, so `config:cache` succeeds and no
 * "Undefined array key" errors occur at runtime.
 */

test('titan-modules config file loads and required keys are present', function () {
    expect(config('titan-modules'))->toBeArray()
        ->and(config('titan-modules.path'))->not->toBeEmpty()
        ->and(config('titan-modules.namespace'))->not->toBeEmpty()
        ->and(config('titan-modules.safe_boot'))->toBeBool()
        ->and(config('titan-modules.discovery'))->toBeArray()
        ->and(config('titan-modules.lifecycle'))->toBeArray();
});

test('titan-ai config file loads and required keys are present', function () {
    expect(config('titan-ai'))->toBeArray()
        ->and(config('titan-ai.default_provider'))->not->toBeEmpty()
        ->and(config('titan-ai.features'))->toBeArray()
        ->and(config('titan-ai.guardrails'))->toBeArray()
        ->and(config('titan-ai.agents'))->toBeArray()
        ->and(config('titan-ai.tools'))->toBeArray()
        ->and(config('titan-ai.permissions'))->toBeArray()
        ->and(config('titan-ai.audit'))->toBeArray()
        ->and(config('titan-ai.vector_store'))->toBeArray()
        ->and(config('titan-ai.vector_store.driver'))->not->toBeEmpty();
});

test('titan-model-runtime config file loads and required keys are present', function () {
    expect(config('titan-model-runtime'))->toBeArray()
        ->and(config('titan-model-runtime.providers'))->toBeArray()->not->toBeEmpty()
        ->and(config('titan-model-runtime.routing'))->toBeArray()
        ->and(config('titan-model-runtime.failover'))->toBeArray()
        ->and(config('titan-model-runtime.timeouts'))->toBeArray()
        ->and(config('titan-model-runtime.metrics'))->toBeArray();
});

test('titan-ai default_provider matches a configured provider in titan-model-runtime', function () {
    $defaultProvider = config('titan-ai.default_provider');
    $providers = array_keys(config('titan-model-runtime.providers', []));

    expect($providers)->toContain($defaultProvider);
});

test('titan-model-runtime openai provider has required keys', function () {
    $openai = config('titan-model-runtime.providers.openai');

    expect($openai)->toBeArray()
        ->toHaveKeys(['enabled', 'api_key', 'base', 'model']);
});

test('titan-modules safe_boot defaults to true', function () {
    expect(config('titan-modules.safe_boot'))->toBeTrue();
});

test('titan-ai guardrails default_allow is a non-empty array', function () {
    expect(config('titan-ai.guardrails.default_allow'))
        ->toBeArray()
        ->not->toBeEmpty();
});
