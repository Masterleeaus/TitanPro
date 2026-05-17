<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Create;

use Modules\Dispatch\Events\Domain\DispatchWorkOrderCreated;
use Modules\Dispatch\Models\DispatchWorkOrder;

class CreateDispatchWorkOrderAction
{
    /** @param array<string,mixed> $payload */
    public function execute(array $payload): DispatchWorkOrder
    {
        $payload['status'] ??= 'draft';
        $payload['priority'] ??= 'normal';

        if (blank($payload['reference'] ?? null)) {
            $payload['reference'] = $this->nextReference();
        }

        $workOrder = DispatchWorkOrder::query()->create($payload);

        event(new DispatchWorkOrderCreated($workOrder));

        return $workOrder;
    }

    private function nextReference(): string
    {
        return 'DWO-'.now()->format('Ymd-His').'-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
    }
}
