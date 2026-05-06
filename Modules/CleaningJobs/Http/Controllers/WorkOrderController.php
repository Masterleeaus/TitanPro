<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Events\WorkOrderCreated;
use Modules\CleaningJobs\Events\WorkOrderUpdated;
use Modules\CleaningJobs\Events\WorkOrderCompleted;
use Modules\CleaningJobs\Http\Requests\StoreWorkOrderRequest;
use Modules\CleaningJobs\Http\Requests\UpdateWorkOrderRequest;

class WorkOrderController extends Controller
{
    public function index(): View
    {
        $orders = WorkOrder::latest()->paginate(20);
        return view('cleaningjobs::workorders.index', compact('orders'));
    }

    public function create(): View
    {
        return view('cleaningjobs::workorders.create');
    }

    public function store(StoreWorkOrderRequest $request): RedirectResponse
    {
        $order = WorkOrder::create($request->validated());
        event(new WorkOrderCreated($order));

        return redirect()->route('cleaningjobs.orders.show', $order->id)->with('status', 'Work order created');
    }

    public function show(int $id): View
    {
        $order = WorkOrder::with(['tasks', 'parts', 'appointments'])->findOrFail($id);
        return view('cleaningjobs::workorders.show', compact('order'));
    }

    public function edit(int $id): View
    {
        $order = WorkOrder::findOrFail($id);
        return view('cleaningjobs::workorders.edit', compact('order'));
    }

    public function update(UpdateWorkOrderRequest $request, int $id): RedirectResponse
    {
        $order = WorkOrder::findOrFail($id);
        $oldStatus = $order->status;
        $order->update($request->validated());

        if ($oldStatus !== $order->status && in_array($order->status, ['completed', 'done'], true)) {
            event(new WorkOrderCompleted($order));
        }

        event(new WorkOrderUpdated($order));

        return redirect()->route('cleaningjobs.orders.show', $order->id)->with('status', 'Work order updated');
    }

    public function destroy(int $id): RedirectResponse
    {
        $order = WorkOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('cleaningjobs.orders.index')->with('status', 'Work order deleted');
    }

    public function convertToProject(int $id): RedirectResponse
    {
        $order = WorkOrder::with(['tasks'])->findOrFail($id);
        $project = $order->convertToProject();

        if (!$project) {
            return back()->with('error', 'Configured project/task models are missing. Update config(cleaningjobs.models.*).');
        }

        return back()->with('status', 'Converted to Project ID '.$project->id);
    }
}
