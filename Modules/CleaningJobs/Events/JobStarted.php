<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\CleaningJobs\Models\WorkOrder;

class JobStarted
{
    use Dispatchable, SerializesModels;

    public readonly string $company_id;
    public readonly string $actor_id;
    public readonly string $source_type;
    public readonly string $occurred_at;

    public function __construct(
        public readonly WorkOrder $workOrder,
        int $actorId = 0
    ) {
        $this->company_id  = (string) ($workOrder->company_id ?? 0);
        $this->actor_id    = (string) ($actorId ?: auth()->id() ?? 0);
        $this->source_type = 'cleaningjobs';
        $this->occurred_at = now()->toIso8601String();
    }

    public function topic(): string { return 'cleaningjobs.job.started'; }
}
