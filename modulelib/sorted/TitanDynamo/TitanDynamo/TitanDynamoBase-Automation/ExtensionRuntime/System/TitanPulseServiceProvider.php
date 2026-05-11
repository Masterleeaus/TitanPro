<?php

declare(strict_types=1);

namespace App\Extensions\TitanPulse\System;

use App\Extensions\TitanPulse\System\Console\Commands\PulseRunCommand;
use App\Extensions\TitanPulse\System\Console\Commands\PulsePacksCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class TitanPulseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/titan-pulse.php', 'titan-pulse');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PulseRunCommand::class,
                PulsePacksCommand::class,
            ]);

            $this->app->booted(function (): void {
                if (! config('titan-pulse.enabled', true)) {
                    return;
                }

                $schedule = $this->app->make(Schedule::class);
                $schedule->command('titan:pulse-run --limit=' . config('titan-pulse.default_limit', 200))
                    ->everyFiveMinutes()
                    ->withoutOverlapping();
            });
        }
    }
}
