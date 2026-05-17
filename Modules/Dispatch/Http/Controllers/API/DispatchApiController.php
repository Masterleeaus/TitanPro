<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Dispatch\Actions\Create\CreateDispatchWorkOrderAction;
use Modules\Dispatch\Contracts\Services\DispatchSchedulerContract;
use Modules\Dispatch\Contracts\Services\DispatchStatusContract;
use Modules\Dispatch\Http\Requests\ChangeDispatchStatusRequest;
use Modules\Dispatch\Http\Requests\CreateDispatchWorkOrderRequest;
use Modules\Dispatch\Http\Requests\ScheduleDispatchWorkOrderRequest;
use Modules\Dispatch\Http\Resources\DispatchWorkOrderResource;
use Modules\Dispatch\Models\DispatchAppointment;
use Illuminate\Routing\Controller;
use Modules\Dispatch\Actions\Allocation\RecommendTechnicianForJobAction;
use Modules\Dispatch\Actions\Create\BuildTechnicianRouteAction;
use Modules\Dispatch\Actions\Reporting\GenerateDispatchSummaryAction;
use Modules\Dispatch\Actions\Update\RescheduleDispatchAppointmentAction;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\DispatchRoute;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Services\Routing\RouteOptimisationService;
use Modules\Dispatch\Http\Resources\DispatchKpiResource;

class DispatchApiController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $workOrders = DispatchWorkOrder::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('technician_id'), fn ($query) => $query->where('technician_id', $request->integer('technician_id')))
            ->when($request->filled('scheduled_from'), fn ($query) => $query->where('scheduled_for', '>=', $request->date('scheduled_from')))
            ->when($request->filled('scheduled_to'), fn ($query) => $query->where('scheduled_for', '<=', $request->date('scheduled_to')))
            ->latest()
            ->paginate((int) $request->integer('per_page', 25));

        return response()->json(DispatchWorkOrderResource::collection($workOrders)->response()->getData(true));
    }

    public function store(CreateDispatchWorkOrderRequest $request, CreateDispatchWorkOrderAction $action): JsonResponse
    {
        return response()->json([
            'data' => new DispatchWorkOrderResource($action->execute($request->validated())),
        ], 201);
    }

    public function show(DispatchWorkOrder $workOrder): JsonResponse
    {
        return response()->json([
            'data' => new DispatchWorkOrderResource($workOrder->load(['appointments', 'customerLocation', 'primaryShiftAssignment'])),
        ]);
    }

    public function calendar(Request $request): JsonResponse
    {
        $appointments = DispatchAppointment::query()
            ->with(['workOrder', 'technician'])
            ->when($request->filled('technician_id'), fn ($query) => $query->where('technician_id', $request->integer('technician_id')))
            ->when($request->filled('from'), fn ($query) => $query->where('starts_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->where('starts_at', '<=', $request->date('to')))
            ->orderBy('starts_at')
            ->get();

        return response()->json([
            'data' => $appointments->map(fn ($appointment) => [
                'id' => $appointment->id,
                'title' => optional($appointment->workOrder)->title ?? 'Dispatch appointment',
                'start' => optional($appointment->starts_at)->toISOString(),
                'end' => optional($appointment->ends_at)->toISOString(),
                'status' => $appointment->status,
                'technician_id' => $appointment->technician_id,
                'work_order_id' => $appointment->work_order_id,
                'location' => $appointment->location,
            ])->values(),
        ]);
    }

    public function recommendations(Request $request, RecommendTechnicianForJobAction $action): JsonResponse
    {
        return response()->json(['data' => $action->execute($request->all())->values()]);
    }

    public function schedule(ScheduleDispatchWorkOrderRequest $request, DispatchSchedulerContract $scheduler): JsonResponse
    {
        $validated = $request->validated();
        $workOrder = DispatchWorkOrder::query()->findOrFail($validated['work_order_id']);

        return response()->json([
            'data' => $scheduler->schedule(
                $workOrder,
                (int) $validated['technician_id'],
                $validated['starts_at'],
                $validated['ends_at'] ?? null,
                $validated['shift_id'] ?? null,
                $validated
            ),
        ]);
    }

    public function updateStatus(ChangeDispatchStatusRequest $request, AssignShift $assignment, DispatchStatusContract $statusService): JsonResponse
    {
        $validated = $request->validated();

        return response()->json(['data' => $statusService->change($assignment, $validated['status'], $validated['notes'] ?? null, optional($request->user())->id)]);
    }

    public function buildRoute(Request $request, BuildTechnicianRouteAction $action): JsonResponse
    {
        $validated = $request->validate([
            'technician_id' => ['required', 'integer'],
            'route_date' => ['required', 'date'],
            'appointment_ids' => ['array'],
            'appointment_ids.*' => ['integer'],
            'name' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'company_id' => ['nullable', 'integer'],
        ]);

        return response()->json([
            'data' => $action->execute(
                (int) $validated['technician_id'],
                $validated['route_date'],
                $validated['appointment_ids'] ?? [],
                $validated
            )->load(['technician', 'stops.customerLocation', 'stops.appointment']),
        ]);
    }

    public function resequenceRoute(DispatchRoute $route, RouteOptimisationService $service): JsonResponse
    {
        return response()->json(['data' => $service->resequenceRoute($route)]);
    }

    public function kpis(Request $request, GenerateDispatchSummaryAction $action): JsonResponse
    {
        $summary = $action->handle($request->string('from')->toString() ?: null, $request->string('to')->toString() ?: null);

        return response()->json(['data' => new DispatchKpiResource($summary)]);
    }

    public function rescheduleAppointment(Request $request, DispatchAppointment $appointment, RescheduleDispatchAppointmentAction $action): JsonResponse
    {
        $validated = $request->validate([
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'technician_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'string'],
        ]);

        return response()->json([
            'data' => $action->handle($appointment, $validated['starts_at'], $validated['ends_at'] ?? null, $validated),
        ]);
    }
}
