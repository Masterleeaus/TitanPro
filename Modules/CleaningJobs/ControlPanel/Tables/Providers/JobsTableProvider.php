<?php

namespace Modules\CleaningJobs\ControlPanel\Tables\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\CleaningJobs\Models\WorkOrder;

class JobsTableProvider extends BaseTableProvider
{
    public function query(): Builder
    {
        return WorkOrder::query()->latest()->limit(10);
    }

    public function transform(): Collection
    {
        return $this->query()->get()->map(fn (WorkOrder $order): array => [
            'id' => $order->id,
            'reference' => $order->wo_id ?? 'CJ-'.$order->id,
            'title' => $order->title ?? $order->wo_detail,
            'status' => $order->status,
            'priority' => $order->priority,
            'scheduled_for' => optional($order->scheduled_for)->toDateTimeString(),
            'due_by' => optional($order->due_by ?? $order->due_date)->toDateTimeString(),
        ]);
    }
}
