<?php

namespace Modules\CleaningJobs\ControlPanel\Widgets;

use Illuminate\Support\Collection;
use Modules\CleaningJobs\Models\WORequest;
use Modules\CleaningJobs\Models\WorkOrder;

class OperationalWidgets
{
    public function todaysJobs(): Collection
    {
        return WorkOrder::query()
            ->whereDate('scheduled_for', today())
            ->latest('scheduled_for')
            ->limit(5)
            ->get()
            ->map(fn (WorkOrder $order): array => [
                'id' => $order->id,
                'reference' => $order->wo_id ?? 'CJ-'.$order->id,
                'title' => $order->title ?? $order->wo_detail ?? 'Cleaning job',
                'status' => $order->status,
                'priority' => $order->priority,
                'scheduled_for' => optional($order->scheduled_for)->toDateTimeString(),
                'technician_id' => $order->technician_id,
            ]);
    }

    public function pendingRequests(): Collection
    {
        return WORequest::query()
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest('due_date')
            ->limit(5)
            ->get()
            ->map(fn (WORequest $request): array => [
                'id' => $request->id,
                'request_detail' => $request->request_detail,
                'priority' => $request->priority,
                'status' => $request->status,
                'due_date' => optional($request->due_date)->toDateString(),
                'assign' => $request->assign,
            ]);
    }
}
