<?php

namespace Modules\CallingAgent\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CallingAgent\Contracts\RealtimeVoiceProvider;
use Modules\CallingAgent\Contracts\STTProvider;
use Modules\CallingAgent\Contracts\TTSProvider;
use Modules\CallingAgent\Contracts\TelephonyProvider;
use Modules\CallingAgent\Services\Calendar\CalendarProviderManager;
use Modules\CallingAgent\Services\TwilioChannelService;
use Modules\CallingAgent\Services\ReceptionistOrchestrator;
use Modules\CallingAgent\AI\Agents\ReceptionistAgent;
use Modules\CallingAgent\Services\Realtime\RealtimeSessionTokenService;
use Modules\CallingAgent\Services\Contracts\TitanEchoVoiceServiceContract;
use Modules\CallingAgent\Services\Providers\ElevenLabs\ElevenLabsRealtimeVoiceProvider;
use Modules\CallingAgent\Services\Providers\OpenAI\OpenAIRealtimeProvider;
use Modules\CallingAgent\Services\Providers\ProviderFailoverManager;
use Modules\CallingAgent\Services\Providers\Twilio\UnifiedTwilioProvider;
use Modules\CallingAgent\Services\Providers\VoiceProviderManager;
use Modules\CallingAgent\Services\TitanEchoVoiceService;
use Modules\CallingAgent\Services\TransferRoutingService;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'calling-agent.config');
        $this->mergeConfigFrom(__DIR__.'/../Config/routes.php', 'calling-agent.routes');
        $this->mergeConfigFrom(__DIR__.'/../Config/features.php', 'calling-agent.features');
        $this->mergeConfigFrom(__DIR__.'/../Config/ai.php', 'calling-agent.ai');
        $this->mergeConfigFrom(__DIR__.'/../Config/providers.php', 'calling-agent.providers');
        $this->mergeConfigFrom(__DIR__.'/../Config/permissions.php', 'calling-agent.permissions');
        $this->mergeConfigFrom(__DIR__.'/../Config/routing.php', 'calling-agent.routing');
        $this->mergeConfigFrom(__DIR__.'/../Config/personas.php', 'calling-agent.personas');
        $this->mergeConfigFrom(__DIR__.'/../Config/ui.php', 'calling-agent.ui');

        $this->app->register(EventServiceProvider::class);
        $this->app->register(BillingServiceProvider::class);
        $this->app->register(PolicyServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(WorkflowServiceProvider::class);
        $this->app->register(AutomationServiceProvider::class);
        $this->app->register(TenancyServiceProvider::class);
        $this->app->register(SearchServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);
        $this->app->register(ModuleBootServiceProvider::class);

        $this->app->singleton(TwilioChannelService::class);
        $this->app->singleton(ReceptionistAgent::class);
        $this->app->singleton(ReceptionistOrchestrator::class);
        $this->app->singleton(RealtimeSessionTokenService::class);
        $this->app->singleton(UnifiedTwilioProvider::class, fn ($app) => new UnifiedTwilioProvider(
            $app->make(TwilioChannelService::class),
        ));
        $this->app->singleton(ElevenLabsRealtimeVoiceProvider::class);
        $this->app->singleton(OpenAIRealtimeProvider::class);
        $this->app->singleton(ProviderFailoverManager::class);
        $this->app->singleton(CalendarProviderManager::class);
        $this->app->singleton(TransferRoutingService::class);
        $this->app->singleton(VoiceProviderManager::class, function ($app) {
            return (new VoiceProviderManager())
                ->register('twilio', $app->make(UnifiedTwilioProvider::class))
                ->register('elevenlabs', $app->make(ElevenLabsRealtimeVoiceProvider::class))
                ->register('openai', $app->make(OpenAIRealtimeProvider::class));
        });
        $this->app->bind(TelephonyProvider::class, UnifiedTwilioProvider::class);
        $this->app->bind(TTSProvider::class, fn ($app) => $app->make(ElevenLabsRealtimeVoiceProvider::class));
        $this->app->bind(STTProvider::class, fn ($app) => $app->make(OpenAIRealtimeProvider::class));
        $this->app->bind(RealtimeVoiceProvider::class, function ($app) {
            return match (config('calling-agent.providers.realtime', 'elevenlabs')) {
                'openai' => $app->make(OpenAIRealtimeProvider::class),
                default => $app->make(ElevenLabsRealtimeVoiceProvider::class),
            };
        });

        // Bind the TitanEchoVoice service contract to its implementation
        $this->app->bind(TitanEchoVoiceServiceContract::class, TitanEchoVoiceService::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/tenant.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/internal.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/channels.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/console.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'calling-agent');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'calling-agent');
    }
}
