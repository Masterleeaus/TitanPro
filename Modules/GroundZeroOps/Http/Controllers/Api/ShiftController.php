<?php

namespace Modules\GroundZeroOps\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Modules\GroundZeroOps\Actions\EndShiftAction;
use Modules\GroundZeroOps\Actions\StartShiftAction;
use Modules\GroundZeroOps\Http\Requests\EndShiftRequest;
use Modules\GroundZeroOps\Http\Requests\StartShiftRequest;
use Modules\GroundZeroOps\Http\Resources\ShiftResource;
use Modules\GroundZeroOps\Models\Shift;

class ShiftController extends Controller
{
    public function start(StartShiftRequest $request, StartShiftAction $action): JsonResponse
    {
        $companyId = (int) ($request->user()?->company_id ?? 0);

        $shift = $action->execute(
            technicianId: (int) $request->integer('technician_id'),
            actorId: (int) ($request->user()?->id ?? 0),
            companyId: $companyId,
        );

        return (new ShiftResource($shift))->response()->setStatusCode(201);
    }

    public function end(EndShiftRequest $request, Shift $shift, EndShiftAction $action): JsonResponse
    {
        $this->authorizeShift($request, $shift);

        $shift = $action->execute($shift, (int) ($request->user()?->id ?? 0));

        return (new ShiftResource($shift))->response()->setStatusCode(200);
    }

    private function authorizeShift(EndShiftRequest $request, Shift $shift): void
    {
        Gate::forUser($request->user())->authorize('update', $shift);
    }
}
