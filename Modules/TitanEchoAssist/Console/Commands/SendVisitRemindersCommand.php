<?php

namespace Modules\TitanEchoAssist\Console\Commands;

use App\Models\Job;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Modules\TitanEchoAssist\Listeners\PortalAutomation\HandleVisitTomorrow;

class SendVisitRemindersCommand extends Command
{
    protected $signature = 'chatbot:portal:send-visit-reminders';

    protected $description = 'Trigger chatbot portal automations for tomorrow visits';

    public function __construct(private readonly HandleVisitTomorrow $handler)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow(config('app.timezone', 'UTC'));
        $count = 0;

        Job::query()
            ->whereIn('status', [Job::STATUS_SCHEDULED, Job::STATUS_ASSIGNED])
            ->whereNotNull('scheduled_at')
            ->whereBetween('scheduled_at', [$tomorrow->copy()->startOfDay(), $tomorrow->copy()->endOfDay()])
            ->with('customer')
            ->chunkById(100, function ($jobs) use (&$count): void {
                foreach ($jobs as $job) {
                    $this->handler->handle($job);
                    $count++;
                }
            });

        $this->info("Processed {$count} visit reminder portal automation trigger(s).");

        return self::SUCCESS;
    }
}
