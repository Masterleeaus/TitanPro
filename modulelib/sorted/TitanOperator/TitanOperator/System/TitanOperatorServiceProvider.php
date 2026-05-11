<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System;

use App\Domains\Marketplace\Contracts\ExtensionRegisterKeyProviderInterface;
use App\Extensions\TitanOperator\System\Http\Controllers\Api\TitanOperatorApplicationController;
use App\Extensions\TitanOperator\System\Http\Controllers\Api\TitanOperatorFrameController;
use App\Extensions\TitanOperator\System\Http\Controllers\Api\TitanOperatorWorkflowController;
use App\Extensions\TitanOperator\System\Http\Controllers\AvatarController;
use App\Extensions\TitanOperator\System\Http\Controllers\TitanOperatorController;
use App\Extensions\TitanOperator\System\Http\Controllers\TitanOperatorCustomerController;
use App\Extensions\TitanOperator\System\Http\Controllers\TitanOperatorKnowledgeBaseArticleController;
use App\Extensions\TitanOperator\System\Http\Controllers\TitanOperatorMultiChannelController;
use App\Extensions\TitanOperator\System\Http\Controllers\TitanOperatorTrainController;
use App\Extensions\TitanOperator\System\Http\Controllers\Dashboard\TitanOperatorAutomationSettingsController;
use App\Extensions\TitanOperator\System\Http\Controllers\Dashboard\TitanOperatorIntegrationsController;
use App\Extensions\TitanOperator\System\Http\Controllers\Dashboard\TitanOperatorWorkflowSettingsController;
use App\Extensions\TitanOperator\System\Http\Middleware\LanguageMiddleware;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorKnowledgeBaseArticle;
use App\Extensions\TitanOperator\System\Policies\TitanOperatorKnowledgeBaseArticlePolicy;
use App\Extensions\TitanOperator\System\Policies\TitanOperatorPolicy;
use App\Helpers\Classes\Helper;
use App\Http\Middleware\CheckTemplateTypeAndPlan;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TitanOperatorServiceProvider extends ServiceProvider implements ExtensionRegisterKeyProviderInterface
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
            ->registerPolicies()
            ->registerCommand();

    }

    public function registerPolicies(): self
    {
        Gate::policy(TitanOperator::class, TitanOperatorPolicy::class);
        Gate::policy(TitanOperatorKnowledgeBaseArticle::class, TitanOperatorKnowledgeBaseArticlePolicy::class);

        return $this;
    }

    public function registerCommand(): static
    {
        if (Helper::appIsDemo()) {
            $this->commands([
                Console\Commands\ClearDemoModeCommand::class,
            ]);

            //            if ($this->app->runningInConsole()) {
            //                $this->app->booted(function () {
            //                    $schedule = $this->app->make(Schedule::class);
            //                    $schedule->command('app:clear-titan_operator-demo-mode')->everyMinute();
            //                });
            //            }
        }

        return $this;
    }

    public function publishAssets(): static
    {
        $this->publishes([
            __DIR__ . '/../resources/assets/js'     => public_path('vendor/titan-operator/js'),
            __DIR__ . '/../resources/assets/images' => public_path('vendor/titan-operator/images'),
            __DIR__ . '/../resources/assets/icons'  => public_path('vendor/titan-operator/icons'),
            __DIR__ . '/../resources/assets/icons'  => public_path('vendor/titan-operator-multi-channel/icons'),
        ], 'extension');

        // Optional: agent and voice add-ons live inside this combined extension
        $this->publishes([
            __DIR__ . '/../resources/assets/agent' => public_path('vendor/titan-operator-agent'),
        ], 'extension');

        $this->publishes([
            __DIR__ . '/../resources/assets/voice' => public_path('vendor/titan-operator-voice'),
        ], 'extension');

        return $this;
    }

    public function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/titan_operator.php', $this->registerKey());
        // Workflow registry (horizontal, webhook-first)
        $this->mergeConfigFrom(__DIR__ . '/../config/workflows.php', $this->registerKey().'.workflows');
        // Tool registry (metadata + defaults)
        $this->mergeConfigFrom(__DIR__ . '/../config/tools.php', $this->registerKey().'.tools');

        return $this;
    }

    protected function registerTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', $this->registerKey());

        return $this;
    }

    public function registerViews(): static
    {
        // Base titan_operator views
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], $this->registerKey());

        // Channel cards (kept as view namespaces for backward compatibility with existing includes)
        $this->loadViewsFrom([__DIR__ . '/../resources/views/channels/whatsapp'], 'whatsapp-channel');
        $this->loadViewsFrom([__DIR__ . '/../resources/views/channels/telegram'], 'telegram-channel');
        $this->loadViewsFrom([__DIR__ . '/../resources/views/channels/messenger'], 'messenger-channel');

        // Agent + Voice add-ons
        $this->loadViewsFrom([__DIR__ . '/../resources/views/agent'], 'titan_operator_agent');
        $this->loadViewsFrom([__DIR__ . '/../resources/views/voice'], 'titan_operator-voice');

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
                'middleware' => 'web',
            ], function (Router $router) {
                $router
                    ->controller(TitanOperatorFrameController::class)
                    ->group(function (Router $router) {
                        $router->get('titan_operator/{titan_operator:uuid}/frame', 'frame')->name('titan_operator.frame');
                    });
            })
            ->group([
                'middleware'     => ['api', LanguageMiddleware::class],
                'prefix'         => 'api/v2/titan_operator',
                'as'             => 'api.v2.titan_operator.',
                'controller'     => TitanOperatorApplicationController::class,
            ], function (Router $router) {
                $router->get('{titan_operator:uuid}', 'index')->name('index');
                $router->get('{titan_operator:uuid}/articles', 'articles')->name('articles');
                $router->get('{titan_operator:uuid}/articles/{id}/show', 'showArticles')->name('articles.show');
                $router->get('{titan_operator:uuid}/session/{sessionId}', 'indexSession')->name('index.session');
                $router->post('{titan_operator:uuid}/session/{sessionId}/conversation', 'conversionStore')->name('conversion.store');
                $router->post('{titan_operator:uuid}/session/{sessionId}/conversation/connect', 'connectSupport')->name('conversion.connect.support');
                $router->get('{titan_operator:uuid}/session/{sessionId}/conversation/{operatorConversation}', 'conversion')->name('conversion.show');
                $router->get('{titan_operator:uuid}/session/{sessionId}/conversation/{operatorConversation}/messages', 'messages')->name('conversion.messages');
                $router->get('{titan_operator:uuid}/session/{sessionId}/conversation/{operatorConversation}/export', 'export')->name('conversion.export');
                $router->post('{titan_operator:uuid}/session/{sessionId}/conversation/{operatorConversation}/messages', 'storeMessage')->name('conversion.store.message');
                $router->post('{titan_operator:uuid}/session/{sessionId}/conversation/{operatorConversation}/file', 'storeFile')->name('conversion.store.file');
                $router->post('{titan_operator:uuid}/session/{sessionId}/send-email', 'sendEmail')->name('send-email.store');
                $router->any('{titan_operator:uuid}/session/{sessionId}/enable-sound', 'enableSound')->name('enable-sound');
                $router->post('{titan_operator:uuid}/session/{sessionId}/collect-email', 'collectEmail')->name('collect.email');
            })

            // Workflows (horizontal, webhook-first)
            ->group([
                'middleware'     => ['api', LanguageMiddleware::class],
                'prefix'         => 'api/v2/titan_operator',
                'as'             => 'api.v2.titan_operator.workflows.',
                'controller'     => TitanOperatorWorkflowController::class,
            ], function (Router $router) {
                $router->get('{titan_operator:uuid}/session/{sessionId}/workflows', 'list')->name('list');
                $router->post('{titan_operator:uuid}/session/{sessionId}/workflows/propose', 'propose')->name('propose');
                $router->post('{titan_operator:uuid}/session/{sessionId}/workflows/confirm', 'confirm')->name('confirm');
                $router->post('{titan_operator:uuid}/session/{sessionId}/workflows/execute', 'execute')->name('execute');
            })

            // Channel webhooks (combined add-ons)
            ->group([
                'middleware'     => ['api', LanguageMiddleware::class],
                'prefix'         => 'api/v2/titan_operator',
                'as'             => 'api.v2.titan_operator.channel.',
            ], function (Router $router) {
                $router->any('{operatorId}/channel/{channelId}/whatsapp', [\App\Extensions\TitanOperator\System\Channels\Whatsapp\Http\Controllers\Webhook\TitanOperatorTwilioController::class, 'handle'])->name('whatsapp.post.handle');
                $router->any('{operatorId}/channel/{channelId}/telegram', [\App\Extensions\TitanOperator\System\Channels\Telegram\Http\Controllers\Webhook\TitanOperatorTelegramWebhookController::class, 'handle'])->name('telegram.post.handle');
                $router->any('{operatorId}/channel/{channelId}/messenger', [\App\Extensions\TitanOperator\System\Channels\Messenger\Http\Controllers\Webhook\TitanOperatorMessengerWebhookController::class, 'handle'])->name('messenger.post.handle');
            })

            ->group([
                'middleware' => ['web', 'auth'],
            ], function (Router $route) {
                $route->controller(TitanOperatorMultiChannelController::class)
                    ->name('dashboard.titan_operator_multi_channel.')
                    ->prefix('dashboard/titan-operator-multi-channel')
                    ->group(function () {
                        Route::any('', 'index')->name('index');
                        Route::POST('delete', 'delete')->name('delete');
                    });
                // Channel connect (store) endpoints
                $route->post('dashboard/titan-operator-multi-channel/whatsapp/store', [\App\Extensions\TitanOperator\System\Channels\Whatsapp\Http\Controllers\TitanOperatorWhatsappController::class, 'store'])->name('dashboard.titan_operator_multi_channel.whatsapp.store');
                $route->post('dashboard/titan-operator-multi-channel/telegram/store', [\App\Extensions\TitanOperator\System\Channels\Telegram\Http\Controllers\TitanOperatorTelegramController::class, 'store'])->name('dashboard.titan_operator_multi_channel.telegram.store');
                $route->post('dashboard/titan-operator-multi-channel/messenger/store', [\App\Extensions\TitanOperator\System\Channels\Messenger\Http\Controllers\TitanOperatorMessengerController::class, 'store'])->name('dashboard.titan_operator_multi_channel.messenger.store');
                $route->group([
                    'prefix'         => 'dashboard/titan-operator',
                    'as'             => 'dashboard.titan_operator.',
                ], function (Router $router) {
                    $router->resource('knowledge-base-article', TitanOperatorKnowledgeBaseArticleController::class);
                    $router->resource('titan_operator-customer', TitanOperatorCustomerController::class);
                    // Workflow settings (horizontal / multi-SaaS)
                    $router->get('{titan_operator}/workflows', [TitanOperatorWorkflowSettingsController::class, 'index'])->name('workflows.index');
                    $router->post('{titan_operator}/workflows', [TitanOperatorWorkflowSettingsController::class, 'update'])->name('workflows.update');

                    // Unified Automation settings (workflows + tools + gateway)
                    $router->get('{titan_operator}/automation', [TitanOperatorAutomationSettingsController::class, 'index'])->name('automation.index');
                    $router->post('{titan_operator}/automation', [TitanOperatorAutomationSettingsController::class, 'update'])->name('automation.update');
                    // Integrations (Channels + Connectors)
                    $router->get('{titan_operator}/integrations', [\App\Extensions\TitanOperator\System\Http\Controllers\Dashboard\TitanOperatorIntegrationsController::class, 'index'])->name('integrations.index');

                });
                $route
                    ->controller(TitanOperatorController::class)
                    ->prefix('dashboard/titan-operator')
                    ->name('dashboard.titan_operator.')
                    ->group(function (Router $route) {
                        $route->get('', 'index')
                            ->name('index')
                            ->middleware(CheckTemplateTypeAndPlan::class);
                        $route->post('', 'store')->name('store');
                        $route->post('update', 'update')->name('update');
                        $route->post('delete', 'delete')->name('delete');

                        // conversation
                        $route->get('conversations', 'conversations')->name('conversations');
                        $route->get('conversations-with-paginate', 'conversationsWithPaginate')->name('conversations.with.paginate');
                        $route->post('conversations/search', 'searchConversation')->name('conversations.search');

                        // ended routes
                        $route->get('{titan_operator}/enbed', 'enbed')->name('enbed');
                    });
                $route
                    ->controller(TitanOperatorTrainController::class)
                    ->prefix('dashboard/titan-operator/train')
                    ->name('dashboard.titan_operator.train.')
                    ->group(function (Router $route) {
                        // train routes
                        $route->get('data', 'trainData')->name('data');
                        $route->post('delete-embedding', 'deleteEmbedding')->name('delete');
                        $route->post('generate-embedding', 'generateEmbedding')->name('generate.embedding');
                        $route->get('{titan_operator}', 'train')->name('index');
                        $route->post('url', 'trainUrl')->name('url');
                        $route->post('file', 'trainFile')->name('file');
                        $route->post('text', 'trainText')->name('text');
                        $route->post('qa', 'trainQa')->name('qa');
                    });
                $route->post('dashboard/titan-operator/avatar/upload', AvatarController::class)
                    ->name('dashboard.titan_operator.upload.avatar');
            });

        return $this;
    }

    private function router(): Router|Route
    {
        return $this->app['router'];
    }

    public function registerKey(): string
    {
        return 'titan_operator';
    }
}
