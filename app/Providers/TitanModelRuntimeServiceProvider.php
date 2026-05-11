<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanCore\AI\Providers\NullChatProvider;
use Modules\TitanCore\AI\Providers\NullEmbeddingProvider;
use Modules\TitanCore\Contracts\AI\ChatProviderContract;
use Modules\TitanCore\Contracts\AI\EmbeddingProviderContract;

/**
 * Bootstraps the Titan model runtime layer.
 *
 * Responsibilities:
 * - Register model-routing logic and inference-engine bindings.
 * - Provide context-pack and evaluation registries.
 * - Bind null providers as safe defaults when no real provider is configured.
 * - Depends on TitanAiRuntimeServiceProvider being registered first.
 */
class TitanModelRuntimeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singletonIf('titan.model.router', fn () => null);

        // Bind null providers as container-level safe defaults.  Real provider
        // bindings registered by module service providers (e.g. OpenAI, local)
        // will override these via their own singletonIf / singleton calls.
        $this->app->singletonIf(
            ChatProviderContract::class,
            fn () => new NullChatProvider(),
        );

        $this->app->singletonIf(
            EmbeddingProviderContract::class,
            fn () => new NullEmbeddingProvider(),
        );
    }

    public function boot(): void
    {
        //
    }
}
