<?php

namespace Modules\CleaningJobs\Events;

use Modules\CleaningJobs\Models\WorkOrder;

class TechnicianCheckedIn
{
    public readonly int $company_id;
    public readonly ?int $actor_id;
    public readonly string $source_type;
    public readonly \DateTimeInterface $occurred_at;

    public function __construct(
        public readonly WorkOrder $workOrder,
        public readonly int $technicianId = 0,
        public readonly ?array $coordinates = null,
        int $companyId = 0,
        ?int $actorId = null,
        string $sourceType = 'action',
    ) {
        $this->company_id = $companyId ?: (int) ($workOrder->company_id ?? 0);
        $this->actor_id = $actorId ?? (auth()->id() ?: null);
        $this->source_type = $sourceType;
        $this->occurred_at = now();
    }

    public function topic(): string
    {
        return 'cleaningjobs.technician_checked_in';
    }
}
