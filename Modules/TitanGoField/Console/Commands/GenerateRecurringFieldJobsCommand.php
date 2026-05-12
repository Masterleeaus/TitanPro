<?php

namespace Modules\TitanGoField\Console\Commands;

use Illuminate\Console\Command;
use Modules\TitanGoField\Jobs\GenerateRecurringFieldJobsJob;

class GenerateRecurringFieldJobsCommand extends Command
{
    protected $signature = 'titango:generate-recurring {--horizon=30 : Days ahead to generate}';
    protected $description = 'Dispatch recurring field job generation for active recurrence rules';

    public function handle(): int
    {
        $horizon = (int) $this->option('horizon');
        GenerateRecurringFieldJobsJob::dispatch($horizon);
        $this->info("Dispatched recurring field job generation (horizon: {$horizon} days).");
        return 0;
    }
}
