<?php

declare(strict_types=1);

namespace Modules\Dispatch\Console\Schedulers;

use Illuminate\Console\Command;
use Modules\Dispatch\Actions\Automation\RaiseDispatchExceptionAction;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Services\Workflow\DispatchSlaService;

class DispatchSlaSweep extends Command
{
    protected $signature = 'dispatch:sla-sweep {--company_id=}';
    protected $description = 'Raise dispatch exceptions for work orders that have breached SLA completion windows.';

    public function handle(DispatchSlaService $sla, RaiseDispatchExceptionAction $raise): int
    {
        $count = 0;

        DispatchWorkOrder::query()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->when($this->option('company_id'), fn ($query, $companyId) => $query->where('company_id', $companyId))
            ->whereNotNull('scheduled_for')
            ->chunkById(100, function ($orders) use ($sla, $raise, &$count): void {
                foreach ($orders as $workOrder) {
                    if (! $sla->isBreached($workOrder)) {
                        continue;
                    }

                    $exists = $workOrder->exceptions()->where('type', 'sla_breach')->where('status', 'open')->exists();
                    if ($exists) {
                        continue;
                    }

                    $raise->handle('sla_breach', 'Work order has breached its configured completion SLA.', $workOrder, null, 'high');
                    $count++;
                }
            });

        $this->info("Raised {$count} SLA exception(s).");

        return self::SUCCESS;
    }
}
