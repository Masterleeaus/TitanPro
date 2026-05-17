<?php

declare(strict_types=1);

namespace Modules\Dispatch\Events\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Dispatch\Models\DispatchWorkOrder;

class WorkOrderScheduled
{
    use Dispatchable;
    use SerializesModels;

    /** @param array<string,mixed> $payload */
    public function __construct(public DispatchWorkOrder $workOrder, public array $payload = []) {}
}
