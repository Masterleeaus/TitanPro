<?php

namespace Modules\TitanGoField\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanGoField\Actions\CheckInFieldJobAction;
use Modules\TitanGoField\Actions\CompleteFieldJobAction;
use Modules\TitanGoField\Actions\CreateFieldJobAction;
use Modules\TitanGoField\Actions\UpdateFieldJobStatusAction;
use Modules\TitanGoField\Models\FieldJob;
use Modules\TitanGoField\Support\DTOs\CreateFieldJobData;

class FieldJobApiController extends Controller
{
    public function __construct(
        private readonly CreateFieldJobAction $createAction,
        private readonly UpdateFieldJobStatusAction $statusAction,
        private readonly CompleteFieldJobAction $completeAction,
        private readonly CheckInFieldJobAction $checkInAction,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', FieldJob::class);

        $jobs = FieldJob::scopeTenant(FieldJob::query(), $request->user()->company_id)
            ->latest()
            ->paginate(50);

        return response()->json($jobs);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', FieldJob::class);

        $validated = $request->validate([
            'type_id'        => ['nullable', 'integer'],
            'client_id'      => ['nullable', 'integer'],
            'technician_id'  => ['nullable', 'integer'],
            'priority'       => ['nullable', 'string'],
            'description'    => ['nullable', 'string'],
            'scheduled_start' => ['nullable', 'date'],
            'scheduled_end'  => ['nullable', 'date'],
            'due_at'         => ['nullable', 'date'],
        ]);

        $user = $request->user();
        $data = CreateFieldJobData::fromArray(
            array_merge($validated, ['company_id' => $user->company_id, 'actor_id' => $user->id])
        );

        $job = $this->createAction->execute($data);

        return response()->json($job, 201);
    }

    public function show(FieldJob $job): JsonResponse
    {
        $this->authorize('view', $job);
        return response()->json($job->load(['serviceParts', 'serviceTasks', 'appointments']));
    }

    public function update(Request $request, FieldJob $job): JsonResponse
    {
        $this->authorize('update', $job);

        $job->update(array_merge(
            $request->only(['description', 'notes', 'priority', 'scheduled_start', 'scheduled_end', 'due_at']),
            ['updated_by' => $request->user()->id]
        ));

        return response()->json($job->refresh());
    }

    public function destroy(FieldJob $job): JsonResponse
    {
        $this->authorize('delete', $job);
        $job->delete();
        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, FieldJob $job): JsonResponse
    {
        $this->authorize('update', $job);
        $request->validate(['status' => 'required|string']);

        $updated = $this->statusAction->execute($job, $request->input('status'), $request->user()->id);

        return response()->json($updated);
    }

    public function checkIn(Request $request, FieldJob $job): JsonResponse
    {
        $this->authorize('start', $job);

        $coords = $request->only(['lat', 'lng']);
        $updated = $this->checkInAction->execute($job, $request->user()->id, $coords ?: null);

        return response()->json($updated);
    }

    public function checkOut(Request $request, FieldJob $job): JsonResponse
    {
        $this->authorize('update', $job);

        $meta              = $job->meta ?? [];
        $meta['check_out'] = ['at' => now()->toIso8601String(), 'actor' => $request->user()->id];
        $job->update(['meta' => $meta]);

        return response()->json($job->refresh());
    }

    public function complete(Request $request, FieldJob $job): JsonResponse
    {
        $this->authorize('complete', $job);
        $updated = $this->completeAction->execute($job, $request->user()->id, $request->input('notes'));
        return response()->json($updated);
    }
}
