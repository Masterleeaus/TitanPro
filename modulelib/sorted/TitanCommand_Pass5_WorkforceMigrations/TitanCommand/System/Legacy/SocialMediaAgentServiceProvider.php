<?php

declare(strict_types=1);

namespace App\Extensions\TitanCommand\System;

use App\Domains\Marketplace\Contracts\ExtensionRegisterKeyProviderInterface;
use App\Domains\Marketplace\Contracts\UninstallExtensionServiceProviderInterface;
use App\Extensions\TitanCommand\System\Console\Commands\CheckPendingImagesCommand;
use App\Extensions\TitanCommand\System\Console\Commands\GenerateAgentPostsCommand;
use App\Extensions\TitanCommand\System\Console\Commands\PostMetricsAnalyzerCommand;
use App\Extensions\TitanCommand\System\Console\Commands\PostPerformanceAdvisorCommand;
use App\Extensions\TitanCommand\System\Console\Commands\SeedDemoDataCommand;
use App\Extensions\TitanCommand\System\Console\Commands\TrendFinderCommand;
use App\Extensions\TitanCommand\System\Console\Commands\UpdateAverageMetricsCommand;
use App\Extensions\TitanCommand\System\Console\Commands\WeeklySocialTrendsCommand;
use App\Extensions\TitanCommand\System\Http\Controllers\TitanCommandAnalysisController;
use App\Extensions\TitanCommand\System\Http\Controllers\TitanCommandChatController;
use App\Extensions\TitanCommand\System\Http\Controllers\TitanCommandChatSettingsController;
use App\Extensions\TitanCommand\System\Http\Controllers\TitanCommandController;
use App\Extensions\TitanCommand\System\Http\Controllers\TitanCommandPostController;
use App\Extensions\TitanCommand\System\Http\Controllers\JobManagerImportController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsDashboardController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsWorkordersController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsRequestsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsTasksController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsPartsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsAssetsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsPermitsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsInspectionsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsChecklistsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsReportsController;
use App\Extensions\TitanCommand\System\Http\Controllers\Jobs\JobsSettingsController;
use App\Extensions\TitanCommand\System\Models\TitanCommand;
use App\Extensions\TitanCommand\System\Policies\TitanCommandPolicy;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TitanCommandLegacyServiceProvider extends ServiceProvider implements ExtensionRegisterKeyProviderInterface, UninstallExtensionServiceProviderInterface
{
    public function register(): void {}

    public function boot(Kernel $kernel): void
    {
        $this->registerTranslations()
            ->registerViews()
            ->registerRoutes()
            ->registerMigrations()
            ->registerPolicies()
            ->registerCommands()
            ->publishAssets();
    }

    protected function registerCommands(): static
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateAgentPostsCommand::class,
                CheckPendingImagesCommand::class,
                PostMetricsAnalyzerCommand::class,
                TrendFinderCommand::class,
                PostPerformanceAdvisorCommand::class,
                WeeklySocialTrendsCommand::class,
                SeedDemoDataCommand::class,
                UpdateAverageMetricsCommand::class,
            ]);

            // exec('php artisan social-media-agent:generate-posts 1');
            // Schedule tasks
            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('social-media-agent:generate-posts')->everyTwoMinutes();
                $schedule->command('social-media-agent:check-pending-images')->everyFiveMinutes();
                $schedule->command('social-media-agent:post-metrics-analyzer')->cron('10 1 */3 * *');
                $schedule->command('social-media-agent:post-performance-advisor')->cron('25 1 */3 * *');
                $schedule->command('social-media-agent:trend-finder')->cron('40 1 */3 * *');
                $schedule->command('social-media-agent:weekly-social-trends')->cron('0 2 */3 * *');
            });
        }

        return $this;
    }

    protected function registerPolicies(): static
    {
        Gate::policy(TitanCommand::class, TitanCommandPolicy::class);

        return $this;
    }

    public function publishAssets(): static
    {
        $this->publishes([
            __DIR__ . '/../resources/assets/images' => public_path('vendor/social-media-agent/images'),
            __DIR__ . '/../resources/assets/videos' => public_path('vendor/social-media-agent/videos'),
        ], 'extension');

        return $this;
    }

    protected function registerTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', $this->registerKey());

        return $this;
    }

    public function registerViews(): static
    {
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], $this->registerKey());

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
                'middleware' => ['web', 'auth'],
            ], function (Router $router) {
                $router
                    ->name('dashboard.user.social-media.agent.')
                    ->prefix('dashboard/user/social-media/agent')
                    ->group(function (Router $router) {
                        // Note: Jobs Manager routes are registered under `/dashboard/user/command/*`
                        // via System/Http/Routes/web.php (keeps Command workspace cleanly separated).

                        // TITAN COMMAND (Jobs Manager) routes
                        Route::middleware(['web', 'auth'])
                            ->prefix('dashboard/user/command')
                            ->name('dashboard.user.command.')
                            ->group(__DIR__ . '/Http/Routes/web.php');


                        Route::get('chat/{id?}', [TitanCommandChatController::class, 'index'])->name('chat.index');
                        // Main CRUD routes
                        Route::get('', [TitanCommandController::class, 'index'])->name('index');
                        Route::get('post-items', [TitanCommandController::class, 'postItems'])->name('post-items');
                        Route::get('create', [TitanCommandController::class, 'create'])->name('create');
                        Route::get('agents', [TitanCommandController::class, 'agents'])->name('agents');
                        Route::get('calendar', [TitanCommandController::class, 'calendar'])->name('calendar');
                        Route::get('posts', [TitanCommandController::class, 'posts'])->name('posts');
                        Route::get('analytics', [TitanCommandController::class, 'analytics'])->name('analytics');
                        Route::get('accounts', [TitanCommandController::class, 'accounts'])->name('accounts');
                        Route::get('jobs', [JobManagerImportController::class, 'index'])->name('jobs.index');
                        Route::post('', [TitanCommandController::class, 'store'])->name('store');
                        Route::get('{agent}/edit', [TitanCommandController::class, 'edit'])->name('edit');
                        Route::put('{agent}', [TitanCommandController::class, 'update'])->name('update');
                        Route::delete('{agent}', [TitanCommandController::class, 'destroy'])->name('destroy');

                        // Wizard AJAX endpoints
                        Route::post('scrape-website', [TitanCommandController::class, 'scrapeWebsite'])->name('scrape-website');
                        Route::post('generate-targets', [TitanCommandController::class, 'generateTargets'])->name('generate-targets');
                        Route::post('preview-post', [TitanCommandController::class, 'previewPost'])->name('preview-post');

                        // Post management
                        Route::post('{agent}/generate-posts', [TitanCommandController::class, 'generatePosts'])->name('generate-posts');
                        Route::post('posts/{post}/approve', [TitanCommandController::class, 'approvePost'])->name('posts.approve');
                        Route::post('{agent}/approve-bulk', [TitanCommandController::class, 'approveBulk'])->name('approve-bulk');
                        Route::delete('posts/{post}/reject', [TitanCommandController::class, 'rejectPost'])->name('posts.reject');
                        Route::post('posts/{post}/duplicate', [TitanCommandController::class, 'duplicatePost'])->name('posts.duplicate');

                        // Sub-item aliases (unbranded semantics)
                        Route::get('sub-items', [TitanCommandController::class, 'posts'])->name('sub-items');
                        Route::post('{agent}/generate-sub-items', [TitanCommandController::class, 'generatePosts'])->name('generate-sub-items');
                        Route::post('sub-items/{post}/confirm', [TitanCommandController::class, 'approvePost'])->name('sub-items.confirm');
                        Route::post('{agent}/confirm-bulk', [TitanCommandController::class, 'approveBulk'])->name('confirm-bulk');
                        Route::delete('sub-items/{post}/reject', [TitanCommandController::class, 'rejectPost'])->name('sub-items.reject');
                        Route::post('sub-items/{post}/duplicate', [TitanCommandController::class, 'duplicatePost'])->name('sub-items.duplicate');

                        // Analyses
                        Route::get('analyses', [TitanCommandAnalysisController::class, 'index'])->name('analyses.index');
                        Route::get('analyses/{analysis}', [TitanCommandAnalysisController::class, 'show'])->name('analyses.show');
                        Route::post('analyses/{analysis}/read', [TitanCommandAnalysisController::class, 'markAsRead'])->name('analyses.mark-read');
                        Route::delete('analyses/{analysis}', [TitanCommandAnalysisController::class, 'destroy'])->name('analyses.destroy');
                        Route::delete('analyses', [TitanCommandAnalysisController::class, 'clearAll'])->name('analyses.clear-all');

                        // API endpoints
                        Route::get('api/pending-count', [TitanCommandController::class, 'getPendingCount'])->name('api.pending-count');
                        Route::get('api/posts', [TitanCommandController::class, 'getPosts'])->name('api.posts');
                        Route::post('api/posts', [TitanCommandController::class, 'storePost'])->name('api.posts.store');
                        Route::post('api/upload-image', [TitanCommandController::class, 'uploadImage'])->name('api.upload-image');
                        Route::put('api/posts/{post}', [TitanCommandController::class, 'updatePost'])->name('api.posts.update');

                        // API aliases (sub-items)
                        Route::get('api/sub-items', [TitanCommandController::class, 'getPosts'])->name('api.sub-items');
                        Route::post('api/sub-items', [TitanCommandController::class, 'storePost'])->name('api.sub-items.store');
                        Route::put('api/sub-items/{post}', [TitanCommandController::class, 'updatePost'])->name('api.sub-items.update');
                        Route::post('api/posts/generate-content', [TitanCommandController::class, 'generatePostContent'])->name('api.posts.generate-content');
                        Route::post('api/posts/{post}/regenerate', [TitanCommandPostController::class, 'regenerateContent'])->name('api.posts.regenerate');
                        Route::post('api/posts/generate-image', [TitanCommandController::class, 'generatePostImage'])->name('api.posts.generate-image');
                        Route::get('api/generation-status', [TitanCommandController::class, 'getGenerationStatus'])->name('api.generation-status');
                    });

                // TITAN COMMAND — JOBS (Command workspace)
                Route::prefix('dashboard/user/command')
                    ->name('dashboard.user.command.')
                    ->group(function () {
                        $routesFile = __DIR__ . '/Http/Routes/web.php';
                        if (is_file($routesFile)) {
                            require $routesFile;
                        }
                    });
            });

        $this->router()
            ->group([
                'middleware' => ['web', 'auth'],
            ], function (Router $router) {
                $router
                    ->prefix('dashboard/admin/social-media/agent/chat')
                    ->name('dashboard.admin.social-media.agent.chat.')
                    ->middleware('admin')
                    ->group(function (Router $router) {
                        $router->get('settings', [TitanCommandChatSettingsController::class, 'index'])->name('settings');
                        $router->post('settings', [TitanCommandChatSettingsController::class, 'update'])->name('settings.update');
                    });
            });

        // Fal.ai webhook (no auth required)
        $this->router()
            ->group([
                'middleware' => ['api'],
            ], function (Router $router) {
                Route::post('social-media-agent/fal-webhook', [TitanCommandController::class, 'falWebhook'])->name('dashboard.user.social-media.agent.fal-webhook');
            });

        return $this;
    }

    private function router(): Router|Route
    {
        return $this->app['router'];
    }

    public static function uninstall(): void {}

    public function registerKey(): string
    {
        return 'social-media-agent';
    }
}
