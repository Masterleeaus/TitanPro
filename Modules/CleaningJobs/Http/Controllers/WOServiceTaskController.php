<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\CleaningJobs\Models\WOServiceTask;
use Modules\CleaningJobs\Services\WorkOrderTotals;

class WOServiceTaskController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'work_order_id' => ['required', 'integer'],
            'service_task_id' => ['nullable', 'integer'],
            'service_part_id' => ['nullable', 'integer'],
            'service_task' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
            'qty' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['qty'] = $data['qty'] ?? 1;
        $data['rate'] = $data['rate'] ?? 0;
        $data['total'] = $data['qty'] * $data['rate'];

        $item = WOServiceTask::create($data);
        WorkOrderTotals::recalc((int) $item->work_order_id);

        return back()->with('status', 'Service task added');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = WOServiceTask::findOrFail($id);
        $workOrderId = (int) $item->work_order_id;
        $item->delete();
        WorkOrderTotals::recalc($workOrderId);

        return back()->with('status', 'Service task removed');
    }
}
