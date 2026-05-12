<?php

namespace Modules\CleaningJobs\Services;

use InvalidArgumentException;
use Modules\CleaningJobs\Events\WorkOrderCompleted;
use Modules\CleaningJobs\Events\WorkOrderCreated;
use Modules\CleaningJobs\Events\WorkOrderUpdated;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;
use Modules\CleaningJobs\Workflows\Definitions\CleaningJobWorkflow;

class JobLifecycleService
{
    public function __construct(
        private readonly CompanyTenantResolver $tenantResolver,
    ) {}

    public function create(array $payload): WorkOrder
    {
        $payload = $this->tenantResolver->stampAttributes($payload);
        $payload['status'] = $payload['status'] ?? CleaningJobWorkflow::STATUS_OPEN;

        $order = WorkOrder::create($payload);

        event(new WorkOrderCreated($order));

        return $order;
    }

    public function update(WorkOrder $order, array $payload): WorkOrder
    {
        $oldStatus = $order->status;

        if (isset($payload['status']) && ! CleaningJobWorkflow::canTransition($oldStatus, $payload['status'])) {
            throw new InvalidArgumentException("Invalid job status transition from {$oldStatus} to {$payload['status']}.");
        }

        $payload = $this->tenantResolver->stampAttributes($payload);

        $order->fill($payload)->save();

        if ($oldStatus !== $order->status && in_array($order->status, CleaningJobWorkflow::terminalStatuses(), true)) {
            event(new WorkOrderCompleted($order));
        }

        event(new WorkOrderUpdated($order));

        return $order->refresh();
    }
}
