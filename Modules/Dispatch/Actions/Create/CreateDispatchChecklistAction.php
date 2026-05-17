<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Create;

use Modules\Dispatch\Models\DispatchChecklist;
use Modules\Dispatch\Models\DispatchWorkOrder;

class CreateDispatchChecklistAction
{
    public function handle(DispatchWorkOrder $workOrder, array $items = [], string $name = 'Site checklist'): DispatchChecklist
    {
        $checklist = DispatchChecklist::create([
            'company_id' => $workOrder->company_id,
            'work_order_id' => $workOrder->getKey(),
            'name' => $name,
            'status' => 'open',
        ]);

        foreach (array_values($items ?: $this->defaultItems()) as $index => $item) {
            $checklist->items()->create([
                'company_id' => $workOrder->company_id,
                'label' => is_array($item) ? (string) ($item['label'] ?? 'Checklist item') : (string) $item,
                'instructions' => is_array($item) ? ($item['instructions'] ?? null) : null,
                'required' => is_array($item) ? (bool) ($item['required'] ?? true) : true,
                'sort_order' => $index + 1,
            ]);
        }

        return $checklist->load('items');
    }

    protected function defaultItems(): array
    {
        return [
            'Confirm site access and parking',
            'Capture before photos',
            'Complete assigned service tasks',
            'Capture after photos',
            'Collect customer sign-off when required',
        ];
    }
}
