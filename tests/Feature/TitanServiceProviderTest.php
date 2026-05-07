<?php

use App\Providers\TitanAiRuntimeServiceProvider;
use App\Providers\TitanBlueprintServiceProvider;
use App\Providers\TitanModelRuntimeServiceProvider;
use App\Providers\TitanModuleSecurityServiceProvider;
use App\Providers\TitanModuleServiceProvider;

/**
 * Verifies that all five Titan service providers are registered and
 * can be resolved by the application container on a cold boot.
 */
test('TitanModuleServiceProvider is registered and boots successfully', function () {
    $providers = collect($this->app->getLoadedProviders())->keys();

    expect($providers)->toContain(TitanModuleServiceProvider::class);
});

test('TitanBlueprintServiceProvider is registered and boots successfully', function () {
    $providers = collect($this->app->getLoadedProviders())->keys();

    expect($providers)->toContain(TitanBlueprintServiceProvider::class);
});

test('TitanAiRuntimeServiceProvider is registered and boots successfully', function () {
    $providers = collect($this->app->getLoadedProviders())->keys();

    expect($providers)->toContain(TitanAiRuntimeServiceProvider::class);
});

test('TitanModelRuntimeServiceProvider is registered and boots successfully', function () {
    $providers = collect($this->app->getLoadedProviders())->keys();

    expect($providers)->toContain(TitanModelRuntimeServiceProvider::class);
});

test('TitanModuleSecurityServiceProvider is registered and boots successfully', function () {
    $providers = collect($this->app->getLoadedProviders())->keys();

    expect($providers)->toContain(TitanModuleSecurityServiceProvider::class);
});

test('all five Titan providers are resolved in a single container boot', function () {
    $providers = collect($this->app->getLoadedProviders())->keys();

    $titanProviders = [
        TitanModuleServiceProvider::class,
        TitanBlueprintServiceProvider::class,
        TitanAiRuntimeServiceProvider::class,
        TitanModelRuntimeServiceProvider::class,
        TitanModuleSecurityServiceProvider::class,
    ];

    foreach ($titanProviders as $provider) {
        expect($providers)->toContain($provider);
    }
});

test('TitanModuleServiceProvider boots before AI and security providers', function () {
    $providers = array_keys($this->app->getLoadedProviders());

    $moduleIndex   = array_search(TitanModuleServiceProvider::class, $providers);
    $aiIndex       = array_search(TitanAiRuntimeServiceProvider::class, $providers);
    $securityIndex = array_search(TitanModuleSecurityServiceProvider::class, $providers);

    expect($moduleIndex)->toBeLessThan($aiIndex);
    expect($moduleIndex)->toBeLessThan($securityIndex);
});

test('Titan provider singletons are bound to the container', function () {
    expect($this->app->bound('titan.modules'))->toBeTrue();
    expect($this->app->bound('titan.features'))->toBeTrue();
    expect($this->app->bound('titan.blueprints'))->toBeTrue();
    expect($this->app->bound('titan.ai.tools'))->toBeTrue();
    expect($this->app->bound('titan.model.router'))->toBeTrue();
    expect($this->app->bound('titan.module.security'))->toBeTrue();
});
