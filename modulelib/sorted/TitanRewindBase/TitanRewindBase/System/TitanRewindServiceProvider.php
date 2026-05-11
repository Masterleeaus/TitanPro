<?php

declare(strict_types=1);

namespace App\Extensions\TitanRewind\System;

use App\Domains\Marketplace\Contracts\UninstallExtensionServiceProviderInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class TitanRewindServiceProvider extends ServiceProvider implements UninstallExtensionServiceProviderInterface
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/titan-rewind.php', 'titan-rewind');

        $this->app->singleton(Services\RewindAuditService::class);
        $this->app->singleton(Services\RewindCaseService::class);
        $this->app->singleton(Services\RewindFixService::class);
    }

    public function boot(Kernel $kernel): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'titan-rewind');
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], 'titan-rewind');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\ProcessRewindQueue::class,
            ]);

            if (config('titan-rewind.scheduler_enabled', true)) {
                $this->app->booted(function () {
                    $schedule = $this->app->make(Schedule::class);
                    $limit = (int)config('titan-rewind.process_limit', 50);
                    $schedule->command('titanrewind:process --limit=' . $limit)->everyFiveMinutes()->withoutOverlapping();
                });
            }
        }
    }

    public function uninstall(): void
    {
        // template: non-destructive (no drops). Extend if you support uninstall cleanup.
    }
}
