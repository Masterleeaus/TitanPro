<?php

namespace Modules\TitanEchoAssist\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanEchoAssist\AI\Agents\BookingAgent;
use Modules\TitanEchoAssist\AI\Agents\ConversationAgent;
use Modules\TitanEchoAssist\AI\Agents\SupportAgent;
use Modules\TitanEchoAssist\AI\Agents\VoiceAgent;
use Modules\TitanEchoAssist\AI\Memory\ConversationMemoryStore;
use Modules\TitanEchoAssist\Billing\Meters\ConversationMeter;
use Modules\TitanEchoAssist\Billing\Meters\EmbeddingMeter;
use Modules\TitanEchoAssist\Billing\Meters\VoiceSecondsMeter;
use Modules\TitanEchoAssist\Services\ChannelRouter;
use Modules\TitanEchoAssist\Services\ChatbotAnalyticsService;
use Modules\TitanEchoAssist\Services\ConversationRouter;
use Modules\TitanEchoAssist\Services\ConversationSessionManager;
use Modules\TitanEchoAssist\Services\ConversationStateStore;
use Modules\TitanEchoAssist\Services\GeneratorBridge;
use Modules\TitanEchoAssist\Services\MessengerChannel;
use Modules\TitanEchoAssist\Services\TelegramChannel;
use Modules\TitanEchoAssist\Services\Contracts\TitanChatbotServiceContract;
use Modules\TitanEchoAssist\Services\ModuleAgentBindingService;
use Modules\TitanEchoAssist\Services\ModuleAgentControlService;
use Modules\TitanEchoAssist\Services\TitanChatbotService;
use Modules\TitanEchoAssist\Services\TrainingPipeline;
use Modules\TitanEchoAssist\Services\VoiceChannel;
use Modules\TitanEchoAssist\Services\WebchatChannel;
use Modules\TitanEchoAssist\Services\WhatsappChannel;
use Modules\TitanEchoAssist\Billing\Usage\UsageTracker;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $helpers = __DIR__ . '/../Support/helpers.php';
        if (is_file($helpers)) {
            require_once $helpers;
        }

        $this->mergeConfigsFromDir([
            'config'       => 'titan-chatbot',
            'ai'           => 'titan-chatbot.ai',
            'billing'      => 'titan-chatbot.billing',
            'channels'     => 'titan-chatbot.channels',
            'connectors'   => 'titan-chatbot.connectors',
            'features'     => 'titan-chatbot.features',
            'navigation'   => 'titan-chatbot.navigation',
            'observability'=> 'titan-chatbot.observability',
            'routes'       => 'titan-chatbot.routes',
            'security'     => 'titan-chatbot.security',
            'tenancy'      => 'titan-chatbot.tenancy',
        ]);

        // Core services
        $this->app->singleton(ConversationRouter::class);
        $this->app->singleton(ConversationSessionManager::class);
        $this->app->singleton(ConversationStateStore::class);
        $this->app->singleton(GeneratorBridge::class);
        $this->app->singleton(ChannelRouter::class);
        $this->app->singleton(ConversationMemoryStore::class);
        $this->app->singleton(ChatbotAnalyticsService::class);
        $this->app->singleton(TrainingPipeline::class);
        $this->app->singleton(TitanChatbotService::class);
        $this->app->singleton(ModuleAgentBindingService::class);
        $this->app->singleton(ModuleAgentControlService::class);
        $this->app->bind(TitanChatbotServiceContract::class, TitanChatbotService::class);

        // AI agents
        $this->app->singleton(ConversationAgent::class);
        $this->app->singleton(BookingAgent::class);
        $this->app->singleton(SupportAgent::class);
        $this->app->singleton(VoiceAgent::class);

        // Billing meters
        $this->app->singleton(ConversationMeter::class);
        $this->app->singleton(VoiceSecondsMeter::class);
        $this->app->singleton(EmbeddingMeter::class);
        $this->app->singleton(UsageTracker::class);

        // Channel driver bindings
        $this->app->bind('titan.channel.webchat',   WebchatChannel::class);
        $this->app->bind('titan.channel.whatsapp',  WhatsappChannel::class);
        $this->app->bind('titan.channel.telegram',  TelegramChannel::class);
        $this->app->bind('titan.channel.messenger', MessengerChannel::class);
        $this->app->bind('titan.channel.voice',     VoiceChannel::class);
    }

    public function boot(): void
    {
        foreach (['api', 'web', 'admin', 'tenant', 'channels'] as $routeFile) {
            $path = __DIR__ . "/../Routes/{$routeFile}.php";
            if (is_file($path)) {
                $this->loadRoutesFrom($path);
            }
        }

        if (is_dir(__DIR__ . '/../Database/migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        }

        if (is_dir(__DIR__ . '/../Resources/views')) {
            $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'titan-chatbot');
        }

        if (is_dir(__DIR__ . '/../Resources/lang')) {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'titan-chatbot');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\TitanEchoAssist\Console\Commands\AuditTitanChatbotCommand::class,
                \Modules\TitanEchoAssist\Console\Commands\MakeAgentCommand::class,
                \Modules\TitanEchoAssist\Console\Commands\MakeToolCommand::class,
                \Modules\TitanEchoAssist\Console\Commands\ClearMemoryCommand::class,
            ]);
        }
    }

    private function mergeConfigsFromDir(array $map): void
    {
        foreach ($map as $file => $key) {
            $path = __DIR__ . "/../Config/{$file}.php";
            if (is_file($path)) {
                $this->mergeConfigFrom($path, $key);
            }
        }
    }
}
