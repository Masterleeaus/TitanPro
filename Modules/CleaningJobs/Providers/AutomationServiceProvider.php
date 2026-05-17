<?php

namespace Modules\CleaningJobs\Providers;

use App\Platform\Automation\AutomationRegistry;
use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\Automation\Handlers\SendAppointmentReminderHandler;
use Modules\CleaningJobs\Automation\Pipelines\JobAutomationPipeline;
use Modules\CleaningJobs\Automation\Schedulers\DailyJobReminderScheduler;

class AutomationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var AutomationRegistry $registry */
        $registry = $this->app->make(AutomationRegistry::class);

        $registry->register([
            'id' => 'cleaningjobs.job_scheduled_reminder',
            'trigger' => 'cleaningjobs.job.scheduled',
            'handler' => SendAppointmentReminderHandler::class,
            'pipeline' => JobAutomationPipeline::class,
            'retries' => 3,
            'retry_after' => 60,
        ]);

        $registry->register([
            'id' => 'cleaningjobs.daily_job_reminders',
            'trigger' => 'cleaningjobs.job.reminders.daily',
            'handler' => DailyJobReminderScheduler::class,
            'schedule' => 'daily',
            'retries' => 1,
            'retry_after' => 300,
        ]);
    }
}
