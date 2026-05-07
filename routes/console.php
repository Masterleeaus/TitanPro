<?php

use App\Console\Commands\AgentIssueRunner;
use App\Console\Commands\BillingResetMeters;
use App\Console\Commands\PruneDriverLocations;
use App\Console\Commands\SendInvoiceReminders;
use App\Console\Commands\SendJobReminders;
use App\Console\Commands\SendTrialEndingReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendInvoiceReminders::class)->dailyAt('08:00');
Schedule::command(SendJobReminders::class)->hourly();
Schedule::command(PruneDriverLocations::class)->dailyAt('03:00');

// Send trial-ending reminders 3 days before expiry, daily at 09:00
Schedule::command(SendTrialEndingReminders::class, ['--days=3'])->dailyAt('09:00');

// Reset billing meters that have passed their period reset_at — runs daily at midnight
Schedule::command(BillingResetMeters::class)->dailyAt('00:01');

// Agent issue runner — uncomment to enable automatic issue processing every 5 minutes
// Schedule::command(AgentIssueRunner::class)->everyFiveMinutes()->withoutOverlapping();
