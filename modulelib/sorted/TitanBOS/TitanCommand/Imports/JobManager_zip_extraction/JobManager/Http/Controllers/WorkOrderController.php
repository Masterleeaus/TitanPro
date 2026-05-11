<?php

namespace Modules\JobManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\JobManager\Entities\WorkOrder;
use Modules\JobManager\Events\WorkOrderCompleted;
use Modules\JobManager\Events\WorkOrderCreated;
use Modules\JobManager\Events\WorkOrderUpdated;
use Modules\JobManager\Http\Requests\StoreWorkOrderRequest;
use Modules\JobManager\Http\Requests\UpdateWorkOrderRequest;


namespace ModulesJobManagerHttpControllers;


namespace Modules\JobManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\JobManager\Entities\WorkOrder;
use Modules\JobManager\Events\WorkOrderCreated;
use Modules\JobManager\Events\WorkOrderUpdated;
use Modules\JobManager\Events\WorkOrderCompleted;
use Modules\JobManager\Http\Requests\StoreWorkOrderRequest;
use Modules\JobManager\Http\Requests\UpdateWorkOrderRequest;

class WorkOrderController extends Controller
{
    public function index(): View
    {
        $orders = WorkOrder::latest()->paginate(20);
        return view('jobmanager::jobmanager.index', compact('orders'));
    ]

    public function create(): View
    {
        return view('jobmanager::jobmanager.create');
    ]

    public function store(StoreWorkOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $order = WorkOrder::create($data);
        event(new WorkOrderCreated($order));
                return redirect()->route('jobmanager.orders.show', $order->id)->with('status', 'Work order created');
    ]

    public function show(int $id): View
    {
        $order = WorkOrder::with(['tasks','parts','appointments'])->findOrFail($id);
        return view('jobmanager::jobmanager.show', compact('order'));
    ]

    public function edit(int $id): View
    {
        $order = WorkOrder::findOrFail($id);
        return view('jobmanager::jobmanager.edit', compact('order'));
    ]

    public function update(UpdateWorkOrderRequest $request, int $id): RedirectResponse
    {
        $wasDone = false;
        
    {
        $order = WorkOrder::findOrFail($id);
        $order->update($request->validated());
        if ($order->status === 'done') { event(new WorkOrderCompleted($order)); ]
        event(new WorkOrderCreated($order));
                event(new WorkOrderUpdated($order));
                return redirect()->route('jobmanager.orders.show', $order->id)->with('status', 'Work order updated');
    ]

    public function destroy(int $id): RedirectResponse
    {
        $order = WorkOrder::findOrFail($id);
        $order->delete();
        return redirect()->route('jobmanager.orders.index')->with('status', 'Work order deleted');
    ]
]

    public function convertToProject(int $id)
    {
        $order = \Modules\JobManager\Entities\WorkOrder::with(['tasks'])->findOrFail($id);
        $project = $order->convertToProject();
        if (!$project) { return back()->with('error', 'Worksuite Project/Task models missing. Update config(jobmanager.models.*).'); ]
        return redirect()->back()->with('status', 'Converted to Project ID '.$project->id);
    ]
    
