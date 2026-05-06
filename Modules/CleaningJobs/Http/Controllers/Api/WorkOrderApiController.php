<?php

namespace Modules\CleaningJobs\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\CleaningJobs\Http\Requests\StoreWorkOrderRequest;
use Modules\CleaningJobs\Http\Requests\UpdateWorkOrderRequest;
use Modules\CleaningJobs\Http\Resources\WorkOrderResource;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Services\JobLifecycleService;

class WorkOrderApiController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = WorkOrder::query()->latest()->paginate((int) request('per_page', 20));
        return WorkOrderResource::collection($orders)->response();
    }

    public function store(StoreWorkOrderRequest $request, JobLifecycleService $lifecycle): JsonResponse
    {
        $order = $lifecycle->create($request->validated());
        return (new WorkOrderResource($order))->response()->setStatusCode(201);
    }

    public function show(WorkOrder $order): WorkOrderResource
    {
        return new WorkOrderResource($order->load(['tasks', 'parts', 'appointments']));
    }

    public function update(UpdateWorkOrderRequest $request, WorkOrder $order, JobLifecycleService $lifecycle): WorkOrderResource
    {
        return new WorkOrderResource($lifecycle->update($order, $request->validated()));
    }

    public function destroy(WorkOrder $order): JsonResponse
    {
        $order->delete();
        return response()->json(['deleted' => true]);
    }
}
