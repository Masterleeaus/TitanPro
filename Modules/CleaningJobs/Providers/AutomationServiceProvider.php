<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * AutomationServiceProvider registers automation triggers and schedulers for TitanWork.
 *
 * Use this provider to register scheduled tasks, queues and triggers that run
 * automatically based on events such as overdue jobs, missed cleans or supply
 * thresholds. This class currently acts as a placeholder.
 */
class AutomationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register automations here
    }

    public function boot(): void
    {
        // Boot any automation listeners or scheduled tasks
    }
}