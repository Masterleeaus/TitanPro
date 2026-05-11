<?php

namespace Modules\CleaningJobs\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

class CleaningJobsPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'cleaningjobs';
    }

    public function register(Panel $panel): void
    {
        // Resources are registered by the module's FilamentServiceProvider.
    }

    public function boot(Panel $panel): void {}
}
