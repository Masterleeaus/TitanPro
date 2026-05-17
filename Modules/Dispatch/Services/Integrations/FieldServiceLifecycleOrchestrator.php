<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Integrations;

use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\DispatchStatusLog;
use Modules\Dispatch\Models\DispatchWorkOrder;

class FieldServiceLifecycleOrchestrator
{
    public function prepareWorkOrderForDispatch(DispatchWorkOrder $workOrder, array $context = []): array
    {
        $workOrder->forceFill([
            'status' => $context['status'] ?? 'ready_for_dispatch',
            'metadata' => array_merge($workOrder->metadata ?? [], ['dispatch_prepared_at' => now()->toISOString()]),
        ])->save();

        return ['work_order' => $workOrder->refresh()];
    }

    public function closeWorkOrder(DispatchWorkOrder $workOrder, array $context = []): array
    {
        $workOrder->forceFill([
            'status' => $context['status'] ?? 'completed',
            'completed_at' => $context['completed_at'] ?? now(),
        ])->save();

        DispatchStatusLog::query()->create([
            'company_id' => $workOrder->company_id,
            'work_order_id' => $workOrder->id,
            'appointment_id' => DispatchAppointment::query()->where('work_order_id', $workOrder->id)->latest()->value('id'),
            'from_status' => $context['from_status'] ?? null,
            'to_status' => 'completed',
            'changed_by' => $context['changed_by'] ?? null,
            'changed_at' => now(),
            'notes' => $context['notes'] ?? 'Work order closed by Dispatch lifecycle orchestrator.',
        ]);

        return ['work_order' => $workOrder->refresh()];
    }
}
