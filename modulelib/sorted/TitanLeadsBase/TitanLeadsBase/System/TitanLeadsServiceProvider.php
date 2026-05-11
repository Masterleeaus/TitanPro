<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System;

use App\Domains\Marketplace\Contracts\UninstallExtensionServiceProviderInterface;
use App\Extensions\TitanLeads\System\Console\Commands\RunTelegramCampaignCommand;
use App\Extensions\TitanLeads\System\Console\Commands\RunWhatsappCampaignCommand;
use App\Extensions\TitanLeads\System\Console\Commands\RunInvoiceFollowupsCommand;
use App\Extensions\TitanLeads\System\Http\Controllers\Campaign\GenerateController;
use App\Extensions\TitanLeads\System\Http\Controllers\Campaign\TelegramCampaignController;
use App\Extensions\TitanLeads\System\Http\Controllers\Campaign\WhatsappCampaignController;
use App\Extensions\TitanLeads\System\Http\Controllers\InboxController;
use App\Extensions\TitanLeads\System\Http\Controllers\TitanLeadsTrainController;
use App\Extensions\TitanLeads\System\Http\Controllers\MarketingDashboardController;
use App\Extensions\TitanLeads\System\Http\Controllers\Setting\TelegramSettingController;
use App\Extensions\TitanLeads\System\Http\Controllers\Setting\ViewSettingController;
use App\Extensions\TitanLeads\System\Http\Controllers\Setting\WhatsappSettingController;
use App\Extensions\TitanLeads\System\Http\Controllers\Setting\SmsSettingController;
use App\Extensions\TitanLeads\System\Http\Controllers\Setting\VoiceSettingController;
use App\Extensions\TitanLeads\System\Http\Controllers\Setting\EmailSettingController;
use App\Extensions\TitanLeads\System\Http\Controllers\Leads\LeadController as TitanLeadsLeadController;
use App\Extensions\TitanLeads\System\Http\Controllers\Leads\MailboxController as TitanLeadsMailboxController;
use App\Extensions\TitanLeads\System\Http\Controllers\Telegram\TelegramGroupController;
use App\Extensions\TitanLeads\System\Http\Controllers\Telegram\TelegramSubscriberController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\TelegramWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\WhatsappWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\SmsWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\VoiceWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\VoiceRecordingWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\VoiceTranscriptionWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\EmailWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\MessengerWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\OutboxApprovalWebhookController;
use App\Extensions\TitanLeads\System\Http\Controllers\Whatsapp\ContactController;
use App\Extensions\TitanLeads\System\Http\Controllers\Whatsapp\ContactListController;
use App\Extensions\TitanLeads\System\Http\Controllers\Whatsapp\SegmentController;
use App\Extensions\TitanLeads\System\Models\MarketingCampaign;
use App\Extensions\TitanLeads\System\Models\MarketingConversation;
use App\Extensions\TitanLeads\System\Models\Telegram\TelegramGroup;
use App\Extensions\TitanLeads\System\Models\Whatsapp\Contact;
use App\Extensions\TitanLeads\System\Models\Whatsapp\ContactList;
use App\Extensions\TitanLeads\System\Models\Whatsapp\Segment;
use App\Extensions\TitanLeads\System\Policies\ContactListPolicy;
use App\Extensions\TitanLeads\System\Policies\ContactPolicy;
use App\Extensions\TitanLeads\System\Policies\MarketingCampaignPolicy;
use App\Extensions\TitanLeads\System\Policies\MarketingConversationPolicy;
use App\Extensions\TitanLeads\System\Policies\SegmentPolicy;
use App\Extensions\TitanLeads\System\Policies\TelegramGroupPolicy;
use App\Extensions\TitanLeads\System\Http\Controllers\OutboxController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TitanLeadsServiceProvider extends ServiceProvider implements UninstallExtensionServiceProviderInterface
{
    public function register(): void
    {
        $this->registerConfig();
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
                RunInvoiceFollowupsCommand::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('app:run-whatsapp-campaign')->everyTwoMinutes();
                $schedule->command('app:run-telegram-campaign')->everyTwoMinutes();
                $schedule->command('titan-leads:run-invoice-followups')->everyHour();
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
            __DIR__ . '/../resources/assets/images' => public_path('vendor/titan-leads/images'),
        ], 'extension');

        return $this;
    }

    public function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/titan-leads.php', 'titan-leads');

        return $this;
    }

    protected function registerTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'titan-leads');

        return $this;
    }

    public function registerViews(): static
    {
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], 'titan-leads');

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
                'middleware' => 'api',
                'prefix'     => 'api/titan-leads',
                'as'         => 'api.titan-leads.',
            ], function (Router $router) {
                $router->any('whatsapp/{whatsappChannel}/webhook', WhatsappWebhookController::class)->name('whatsapp.webhook');
                $router->any('telegram/webhook/{token}', TelegramWebhookController::class)->name('telegram.webhook');
                $router->any('sms/webhook', SmsWebhookController::class)->name('sms.webhook');
                $router->any('voice/webhook', VoiceWebhookController::class)->name('voice.webhook');
                $router->any('voice/recording', VoiceRecordingWebhookController::class)->name('voice.recording');
                $router->any('voice/transcription', VoiceTranscriptionWebhookController::class)->name('voice.transcription');
                $router->any('email/webhook', EmailWebhookController::class)->name('email.webhook');
                $router->get('messenger/webhook', [MessengerWebhookController::class, 'verify'])->name('messenger.verify');
                $router->post('messenger/webhook', [MessengerWebhookController::class, 'inbound'])->name('messenger.webhook');
                $router->post('outbox/approval/{token}', OutboxApprovalWebhookController::class)->name('outbox.approval');
            });
        
// Titan Leads (Leads + Mailbox)
$this->router()
    ->group([
        'prefix'     => 'dashboard/user/titan-leads/leads',
        'as'         => 'dashboard.user.titan-leads.leads.',
        'middleware' => ['web', 'auth'],
    ], function (Router $router) {
        $router->get('', [TitanLeadsLeadController::class, 'index'])->name('index');
        $router->get('create', [TitanLeadsLeadController::class, 'create'])->name('create');
        $router->post('', [TitanLeadsLeadController::class, 'store'])->name('store');
        $router->get('{lead}', [TitanLeadsLeadController::class, 'show'])->name('show');

        $router->get('{lead}/mailbox', [TitanLeadsMailboxController::class, 'show'])->name('mailbox');
        $router->post('{lead}/mailbox/link-conversation', [TitanLeadsMailboxController::class, 'linkConversation'])->name('mailbox.link');
    });

$this->router()
            ->group([
                'controller' => InboxController::class,
                'prefix'     => 'dashboard/user/titan-leads/inbox',
                'as'         => 'dashboard.user.titan-leads.inbox.',
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
                $router->get('notification/count', 'notification')->name('notification.count');
            });
        $this->router()
            ->group([
                'middleware' => [
                    'web', 'auth',
                ],
                'prefix'     => 'dashboard/user/titan-leads',
                'as'         => 'dashboard.user.titan-leads.',
            ], function (Router $router) {
                $router
                    ->controller(TitanLeadsTrainController::class)
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

                $router->controller(OutboxController::class)
                    ->prefix('outbox')
                    ->name('outbox.')
                    ->group(function (Router $r) {
                        $r->get('', 'index')->name('index');
                        $r->get('list', 'list')->name('list');
                        $r->post('store', 'store')->name('store');
                        $r->post('send', 'sendNow')->name('send');
                        $r->post('approval', 'requestApproval')->name('approval');
                    });
            });
        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'     => 'dashboard/user/titan-leads/settings',
                'as'         => 'dashboard.user.titan-leads.settings.',
            ], function (Router $router) {
                $router->get('', ViewSettingController::class)->name('index');
                $router->post('telegram', TelegramSettingController::class)->name('telegram');
                $router->post('whatsapp', WhatsappSettingController::class)->name('whatsapp');
                $router->post('sms', SmsSettingController::class)->name('sms');
                $router->post('voice', VoiceSettingController::class)->name('voice');
                $router->post('email', EmailSettingController::class)->name('email');
            });

        return $this;
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
