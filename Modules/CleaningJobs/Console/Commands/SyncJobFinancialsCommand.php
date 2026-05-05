<?php

namespace Modules\CleaningJobs\Console\Commands;

use Illuminate\Console\Command;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Services\JobFinancialService;

class SyncJobFinancialsCommand extends Command
{
    protected $signature = 'cleaningjobs:sync-financials {--id=}';
    protected $description = 'Recalculate planning hours, cost, revenue, and health for cleaning jobs.';

    public function handle(JobFinancialService $financials): int
    {
        WorkOrder::query()->when($this->option('id'), fn ($q, $id) => $q->whereKey($id))->chunkById(100, function ($orders) use ($financials) {
            foreach ($orders as $order) $financials->syncWorkOrderFinancials($order);
        });
        $this->info('Cleaning job financials synced.');
        return self::SUCCESS;
    }
}
