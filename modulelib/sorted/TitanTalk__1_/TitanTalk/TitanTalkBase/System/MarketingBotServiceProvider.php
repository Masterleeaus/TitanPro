<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System;

use App\Domains\Marketplace\Contracts\UninstallExtensionServiceProviderInterface;
use App\Extensions\MarketingBot\System\Console\Commands\RunTelegramCampaignCommand;
use App\Extensions\MarketingBot\System\Console\Commands\RunWhatsappCampaignCommand;
use App\Extensions\MarketingBot\System\Http\Controllers\Campaign\GenerateController;
use App\Extensions\MarketingBot\System\Http\Controllers\Campaign\TelegramCampaignController;
use App\Extensions\MarketingBot\System\Http\Controllers\Campaign\WhatsappCampaignController;
use App\Extensions\MarketingBot\System\Http\Controllers\Conversation\AssistantController;
use App\Extensions\MarketingBot\System\Http\Controllers\Conversation\VoiceCommandController;
use App\Extensions\MarketingBot\System\Http\Controllers\InboxController;
use App\Extensions\MarketingBot\System\Http\Controllers\MarketingBotTrainController;
use App\Extensions\MarketingBot\System\Http\Controllers\MarketingDashboardController;
use App\Extensions\MarketingBot\System\Http\Controllers\Setting\TelegramSettingController;
use App\Extensions\MarketingBot\System\Http\Controllers\Setting\ViewSettingController;
use App\Extensions\MarketingBot\System\Http\Controllers\Setting\WhatsappSettingController;
use App\Extensions\MarketingBot\System\Http\Controllers\Telegram\TelegramGroupController;
use App\Extensions\MarketingBot\System\Http\Controllers\Telegram\TelegramSubscriberController;
use App\Extensions\MarketingBot\System\Http\Controllers\Webhook\TelegramWebhookController;
use App\Extensions\MarketingBot\System\Http\Controllers\Webhook\WhatsappWebhookController;
use App\Extensions\MarketingBot\System\Http\Controllers\Webhook\GenericChannelWebhookController;
use App\Extensions\MarketingBot\System\Http\Controllers\Whatsapp\ContactController;
use App\Extensions\MarketingBot\System\Http\Controllers\Whatsapp\ContactListController;
use App\Extensions\MarketingBot\System\Http\Controllers\Whatsapp\SegmentController;
use App\Extensions\MarketingBot\System\Models\MarketingCampaign;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\Telegram\TelegramGroup;
use App\Extensions\MarketingBot\System\Models\Whatsapp\Contact;
use App\Extensions\MarketingBot\System\Models\Whatsapp\ContactList;
use App\Extensions\MarketingBot\System\Models\Whatsapp\Segment;
use App\Extensions\MarketingBot\System\Policies\ContactListPolicy;
use App\Extensions\MarketingBot\System\Policies\ContactPolicy;
use App\Extensions\MarketingBot\System\Policies\MarketingCampaignPolicy;
use App\Extensions\MarketingBot\System\Policies\MarketingConversationPolicy;
use App\Extensions\MarketingBot\System\Policies\SegmentPolicy;
use App\Extensions\MarketingBot\System\Policies\TelegramGroupPolicy;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use App\Extensions\MarketingBot\System\Http\Controllers\Voice\Calls\CallInboxController as TitanTalkCallInboxController;
use App\Extensions\MarketingBot\System\Http\Controllers\Voice\Calls\CallActionsController as TitanTalkCallActionsController;
use App\Extensions\MarketingBot\System\Http\Controllers\Voice\Calls\DialerController as TitanTalkDialerController;
use App\Extensions\MarketingBot\System\Http\Controllers\Voice\Webhooks\CallWebhookController as TitanTalkCallWebhookController;
use App\Extensions\MarketingBot\System\Http\Controllers\Voice\Callbacks\CallbackInboxController as TitanTalkCallbackInboxController;
use App\Extensions\MarketingBot\System\Http\Controllers\Voice\Callbacks\CallbackActionsController as TitanTalkCallbackActionsController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\HandoffInboxController as TitanTalkHandoffInboxController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\OperatorInboxController as TitanTalkOperatorInboxController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\RealtimeSettingsController as TitanTalkRealtimeSettingsController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\WebchatFrameController as TitanTalkWebchatFrameController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\OperatorCopilotController as TitanTalkOperatorCopilotController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\KnowledgeController as TitanTalkKnowledgeController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\AnalyticsController as TitanTalkAnalyticsController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\OperatorActionController as TitanTalkOperatorActionController;
use App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk\QuickMessageController as TitanTalkQuickMessageController;
use App\Extensions\MarketingBot\System\Services\TitanTalk\ChatService as TitanTalkChatService;

class MarketingBotServiceProvider extends ServiceProvider implements UninstallExtensionServiceProviderInterface
{
    private const LEGACY_NAMESPACE = 'marketing-bot';
    private const TITANTALK_NAMESPACE = 'titantalk';
    private const LEGACY_PREFIX = 'marketing-bot';
    private const TITANTALK_PREFIX = 'titan-talk';
    public function register(): void
    {
        $this->registerConfig();
        $this->app->alias(TitanTalkChatService::class, \App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService::class);
    }

    public function boot(Kernel $kernel): void
    {
        $this->registerTranslations()
            ->registerViews()
            ->registerRoutes()
            ->registerMigrations()
            ->publishAssets()
            ->registerCommand()
            ->registerPolicies()
            ->registerComponents();
    }

    public function registerPolicies(): self
    {
        Gate::policy(MarketingCampaign::class, MarketingCampaignPolicy::class);
        Gate::policy(TelegramGroup::class, TelegramGroupPolicy::class);
        Gate::policy(Contact::class, ContactPolicy::class);
        Gate::policy(Segment::class, SegmentPolicy::class);
        Gate::policy(ContactList::class, ContactListPolicy::class);
        Gate::policy(MarketingConversation::class, MarketingConversationPolicy::class);

        return $this;
    }

    public function registerCommand(): static
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunWhatsappCampaignCommand::class,
                RunTelegramCampaignCommand::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('app:run-whatsapp-campaign')->everyTwoMinutes();
                $schedule->command('app:run-telegram-campaign')->everyTwoMinutes();
            });
        }

        return $this;
    }

    public function registerComponents(): static
    {
        //        $this->loadViewComponentsAs('example', []);

        return $this;
    }

    public function publishAssets(): static
    {
        $this->publishes([
            __DIR__ . '/../resources/assets/images' => public_path('vendor/' . self::LEGACY_NAMESPACE . '/images'),
        ], 'extension');

        $this->publishes([
            __DIR__ . '/../resources/assets/images' => public_path('vendor/' . self::TITANTALK_NAMESPACE . '/images'),
        ], 'extension');

        return $this;
    }

    public function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/marketing-bot.php', self::LEGACY_NAMESPACE);
        $this->mergeConfigFrom(__DIR__ . '/../config/titantalk.php', self::TITANTALK_NAMESPACE);
        $this->mergeConfigFrom(__DIR__ . '/../config/titantalk-realtime.php', 'titantalk-realtime');
        $this->mergeConfigFrom(__DIR__ . '/../config/titantalk-webchat.php', 'titantalk-webchat');

        return $this;
    }

    protected function registerTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', self::LEGACY_NAMESPACE);
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', self::TITANTALK_NAMESPACE);

        return $this;
    }

    public function registerViews(): static
    {
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], self::LEGACY_NAMESPACE);
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], self::TITANTALK_NAMESPACE);

        return $this;
    }

    public function registerMigrations(): static
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        return $this;
    }

    private function registerRoutes(): static
    {

        $this->router()
            ->group([
                'middleware' => ['web'],
                'prefix'     => 'titan-talk/webchat',
                'as'         => 'titantalk.webchat.',
            ], function (Router $router) {
                $router->get('frame', TitanTalkWebchatFrameController::class)->name('frame');
            });

        $this->router()
            ->group([
                'middleware' => 'api',
                'prefix'     => 'api/marketing-bot',
                'as'         => 'api.marketing-bot.',
            ], function (Router $router) {
                $router->any('whatsapp/{whatsappChannel}/webhook', WhatsappWebhookController::class)->name('whatsapp.webhook');
                $router->any('telegram/webhook/{token}', TelegramWebhookController::class)->name('telegram.webhook');
                $router->post('{channel}/webhook', GenericChannelWebhookController::class)->name('channel.webhook');
            });
        $this->router()
            ->group([
                'controller' => InboxController::class,
                'prefix'     => 'dashboard/user/marketing-bot/inbox',
                'as'         => 'dashboard.user.marketing-bot.inbox.',
                'middleware' => ['web', 'auth'],
            ], function (Router $router) {
                $router->get('', 'index')->name('index');
                $router->post('conversations/name', 'name')->name('conversations.name.update');
                $router->get('conversations', 'conversations')->name('conversations');
                $router->post('conversations/search', 'searchConversation')->name('conversations.search');
                $router->get('conversations-with-paginate', 'conversationsWithPaginate')->name('conversations.with.paginate');
                $router->get('history', 'history')->name('history');
                $router->post('history', 'store');
                $router->delete('destroy', 'destroy')->name('destroy');
                $router->post('assistant/reply', AssistantController::class)->name('assistant.reply');
                $router->post('assistant/voice-command', VoiceCommandController::class)->name('assistant.voice-command');
                $router->get('notification/count', 'notification')->name('notification.count');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/titan-talk/knowledge',
                'as'         => 'dashboard.user.titan-talk.knowledge.',
            ], function (Router $router) {
                $router->get('', TitanTalkKnowledgeController::class)->name('index');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/titan-talk/analytics',
                'as'         => 'dashboard.user.titan-talk.analytics.',
            ], function (Router $router) {
                $router->get('', TitanTalkAnalyticsController::class)->name('index');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/titan-talk/operator',
                'as'         => 'dashboard.user.titan-talk.operator.',
            ], function (Router $router) {
                $router->get('', [TitanTalkOperatorInboxController::class, 'index'])->name('index');
                $router->get('notification/count', [TitanTalkOperatorInboxController::class, 'notification'])->name('notification.count');
                $router->get('conversations/{conversation}/summary', [TitanTalkOperatorCopilotController::class, 'summary'])->name('conversation.summary');
                $router->get('conversations/{conversation}/context', [TitanTalkOperatorCopilotController::class, 'context'])->name('conversation.context');
                $router->post('conversations/{conversation}/suggest', [TitanTalkOperatorCopilotController::class, 'suggest'])->name('conversation.suggest');
                $router->post('conversations/{conversation}/actions', [TitanTalkOperatorActionController::class, 'store'])->name('conversation.actions.store');
                $router->get('realtime', [TitanTalkRealtimeSettingsController::class, 'index'])->name('realtime.index');
                $router->post('realtime', [TitanTalkRealtimeSettingsController::class, 'update'])->name('realtime.update');
            });

        $this->router()
            ->group([
                'middleware' => [
                    'web', 'auth',
                ],
                'prefix'     => 'dashboard/user/marketing-bot',
                'as'         => 'dashboard.user.marketing-bot.',
            ], function (Router $router) {
                $router
                    ->controller(MarketingBotTrainController::class)
                    ->prefix('train')
                    ->name('train.')
                    ->group(function (Router $route) {
                        $route->get('data', 'trainData')->name('data');
                        $route->post('delete-embedding', 'deleteEmbedding')->name('delete');
                        $route->post('generate-embedding', 'generateEmbedding')->name('generate.embedding');
                        $route->get('{marketingCampaign}', 'train')->name('index');
                        $route->post('url', 'trainUrl')->name('url');
                        $route->post('file', 'trainFile')->name('file');
                        $route->post('text', 'trainText')->name('text');
                        $route->post('qa', 'trainQa')->name('qa');
                    });

                $router->get('', MarketingDashboardController::class)->name('dashboard');
                $router->post('quick-messages', [TitanTalkQuickMessageController::class, 'store'])->name('quick-messages.store');

                $router->post('image/upload', [GenerateController::class, 'image'])->name('image.upload');
                $router->post('generate/content', [GenerateController::class, 'generateContent'])->name('generate.content');

                $router->resource('telegram-campaign', TelegramCampaignController::class);
                $router->resource('whatsapp-campaign', WhatsappCampaignController::class);

                $router->resource('contact', ContactController::class)->except('show', 'create');
                $router->resource('segment', SegmentController::class)->except('show', 'create');
                $router->resource('contact-list', ContactListController::class)->except('show');

                $router->resource('telegram-group', TelegramGroupController::class)
                    ->only(['index', 'destroy']);

                $router->resource('telegram-subscriber', TelegramSubscriberController::class)
                    ->only(['index', 'destroy']);
            });

        $this->router()
            ->group([
                'middleware' => 'api',
                'prefix'     => 'api/marketing-bot/voice',
                'as'         => 'api.marketing-bot.voice.',
            ], function (Router $router) {
                $router->any('webhook/call', TitanTalkCallWebhookController::class)->name('webhook.call');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/marketing-bot/voice',
                'as'         => 'dashboard.user.marketing-bot.voice.',
            ], function (Router $router) {
                $router->get('calls', [TitanTalkCallInboxController::class, 'index'])->name('calls.index');
                $router->get('calls/{id}', [TitanTalkCallInboxController::class, 'show'])->name('calls.show');
                $router->post('calls/{id}/assign', [TitanTalkCallActionsController::class, 'assign'])->name('calls.assign');
                $router->post('calls/{id}/disposition', [TitanTalkCallActionsController::class, 'setDisposition'])->name('calls.disposition');
                $router->post('calls/{id}/callback', [TitanTalkCallActionsController::class, 'setCallback'])->name('calls.callback');
                $router->post('calls/{id}/notes', [TitanTalkCallActionsController::class, 'addNote'])->name('calls.notes');
                $router->get('callbacks', [TitanTalkCallbackInboxController::class, 'index'])->name('callbacks.index');
                $router->get('callbacks/{id}', [TitanTalkCallbackInboxController::class, 'show'])->name('callbacks.show');
                $router->post('callbacks/{id}/assign', [TitanTalkCallbackActionsController::class, 'assign'])->name('callbacks.assign');
                $router->post('callbacks/{id}/due', [TitanTalkCallbackActionsController::class, 'setDue'])->name('callbacks.due');
                $router->post('callbacks/{id}/done', [TitanTalkCallbackActionsController::class, 'done'])->name('callbacks.done');
                $router->post('callbacks/{id}/cancel', [TitanTalkCallbackActionsController::class, 'cancel'])->name('callbacks.cancel');
                $router->get('dialer', [TitanTalkDialerController::class, 'index'])->name('dialer.index');
                $router->post('dialer/call', [TitanTalkDialerController::class, 'call'])->name('dialer.call');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/marketing-bot/settings',
                'as'         => 'dashboard.user.marketing-bot.settings.',
            ], function (Router $router) {
                $router->get('', ViewSettingController::class)->name('index');
                $router->post('telegram', TelegramSettingController::class)->name('telegram');
                $router->post('whatsapp', WhatsappSettingController::class)->name('whatsapp');
            });

        // TitanTalk route aliases preserve the new product identity while keeping legacy MarketingBot paths working.
        $this->registerTitanTalkRouteAliases();

        return $this;
    }

    private function registerTitanTalkRouteAliases(): void
    {
        $this->router()
            ->group([
                'middleware' => 'api',
                'prefix'     => 'api/titan-talk',
                'as'         => 'api.titan-talk.',
            ], function (Router $router) {
                $router->any('whatsapp/{whatsappChannel}/webhook', WhatsappWebhookController::class)->name('whatsapp.webhook');
                $router->any('telegram/webhook/{token}', TelegramWebhookController::class)->name('telegram.webhook');
                $router->post('{channel}/webhook', GenericChannelWebhookController::class)->name('channel.webhook');
                $router->any('voice/webhook/call', TitanTalkCallWebhookController::class)->name('voice.webhook.call');
            });

        $this->router()
            ->group([
                'controller' => InboxController::class,
                'prefix'     => 'dashboard/user/titan-talk/inbox',
                'as'         => 'dashboard.user.titan-talk.inbox.',
                'middleware' => ['web', 'auth'],
            ], function (Router $router) {
                $router->get('', 'index')->name('index');
                $router->post('conversations/name', 'name')->name('conversations.name.update');
                $router->get('conversations', 'conversations')->name('conversations');
                $router->post('conversations/search', 'searchConversation')->name('conversations.search');
                $router->get('conversations-with-paginate', 'conversationsWithPaginate')->name('conversations.with.paginate');
                $router->get('history', 'history')->name('history');
                $router->post('history', 'store');
                $router->delete('destroy', 'destroy')->name('destroy');
                $router->post('assistant/reply', AssistantController::class)->name('assistant.reply');
                $router->post('assistant/voice-command', VoiceCommandController::class)->name('assistant.voice-command');
                $router->get('notification/count', 'notification')->name('notification.count');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/titan-talk/handoffs',
                'as'         => 'dashboard.user.titan-talk.handoffs.',
            ], function (Router $router) {
                $router->get('', [TitanTalkHandoffInboxController::class, 'index'])->name('index');
                $router->post('{id}/assign', [TitanTalkHandoffInboxController::class, 'assign'])->name('assign');
                $router->post('{id}/resolve', [TitanTalkHandoffInboxController::class, 'resolve'])->name('resolve');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/marketing-bot/handoffs',
                'as'         => 'dashboard.user.marketing-bot.handoffs.',
            ], function (Router $router) {
                $router->get('', [TitanTalkHandoffInboxController::class, 'index'])->name('index');
                $router->post('{id}/assign', [TitanTalkHandoffInboxController::class, 'assign'])->name('assign');
                $router->post('{id}/resolve', [TitanTalkHandoffInboxController::class, 'resolve'])->name('resolve');
            });
        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/marketing-bot/knowledge',
                'as'         => 'dashboard.user.marketing-bot.knowledge.',
            ], function (Router $router) {
                $router->get('', TitanTalkKnowledgeController::class)->name('index');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/marketing-bot/analytics',
                'as'         => 'dashboard.user.marketing-bot.analytics.',
            ], function (Router $router) {
                $router->get('', TitanTalkAnalyticsController::class)->name('index');
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/marketing-bot/operator',
                'as'         => 'dashboard.user.marketing-bot.operator.',
            ], function (Router $router) {
                $router->get('', [TitanTalkOperatorInboxController::class, 'index'])->name('index');
                $router->get('notification/count', [TitanTalkOperatorInboxController::class, 'notification'])->name('notification.count');
                $router->get('conversations/{conversation}/summary', [TitanTalkOperatorCopilotController::class, 'summary'])->name('conversation.summary');
                $router->get('conversations/{conversation}/context', [TitanTalkOperatorCopilotController::class, 'context'])->name('conversation.context');
                $router->post('conversations/{conversation}/suggest', [TitanTalkOperatorCopilotController::class, 'suggest'])->name('conversation.suggest');
                $router->post('conversations/{conversation}/actions', [TitanTalkOperatorActionController::class, 'store'])->name('conversation.actions.store');
                $router->get('realtime', [TitanTalkRealtimeSettingsController::class, 'index'])->name('realtime.index');
                $router->post('realtime', [TitanTalkRealtimeSettingsController::class, 'update'])->name('realtime.update');
            });


        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/titan-talk',
                'as'         => 'dashboard.user.titan-talk.',
            ], function (Router $router) {
                $router->get('', MarketingDashboardController::class)->name('dashboard');
                $router->post('quick-messages', [TitanTalkQuickMessageController::class, 'store'])->name('quick-messages.store');
                $router->get('settings', ViewSettingController::class)->name('settings.index');
                $router->post('settings/telegram', TelegramSettingController::class)->name('settings.telegram');
                $router->post('settings/whatsapp', WhatsappSettingController::class)->name('settings.whatsapp');
                $router->get('voice/calls', [TitanTalkCallInboxController::class, 'index'])->name('voice.calls.index');
                $router->get('voice/calls/{id}', [TitanTalkCallInboxController::class, 'show'])->name('voice.calls.show');
                $router->post('voice/calls/{id}/assign', [TitanTalkCallActionsController::class, 'assign'])->name('voice.calls.assign');
                $router->post('voice/calls/{id}/disposition', [TitanTalkCallActionsController::class, 'setDisposition'])->name('voice.calls.disposition');
                $router->post('voice/calls/{id}/callback', [TitanTalkCallActionsController::class, 'setCallback'])->name('voice.calls.callback');
                $router->post('voice/calls/{id}/notes', [TitanTalkCallActionsController::class, 'addNote'])->name('voice.calls.notes');
                $router->get('voice/callbacks', [TitanTalkCallbackInboxController::class, 'index'])->name('voice.callbacks.index');
                $router->get('voice/callbacks/{id}', [TitanTalkCallbackInboxController::class, 'show'])->name('voice.callbacks.show');
                $router->post('voice/callbacks/{id}/assign', [TitanTalkCallbackActionsController::class, 'assign'])->name('voice.callbacks.assign');
                $router->post('voice/callbacks/{id}/due', [TitanTalkCallbackActionsController::class, 'setDue'])->name('voice.callbacks.due');
                $router->post('voice/callbacks/{id}/done', [TitanTalkCallbackActionsController::class, 'done'])->name('voice.callbacks.done');
                $router->post('voice/callbacks/{id}/cancel', [TitanTalkCallbackActionsController::class, 'cancel'])->name('voice.callbacks.cancel');
                $router->get('voice/dialer', [TitanTalkDialerController::class, 'index'])->name('voice.dialer.index');
                $router->post('voice/dialer/call', [TitanTalkDialerController::class, 'call'])->name('voice.dialer.call');
            });
    }

    private function router(): Router|Route
    {
        return $this->app['router'];
    }

    public static function uninstall(): void
    {
        // TODO: Implement uninstall() method.
    }
}
