<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollRunGuardServiceContract;

class PayrollFinalizationController extends Controller
{
    public function inspect(Request $request, PayrollRunGuardServiceContract $guard): JsonResponse
    {
        return response()->json($guard->inspect($request->all())->toArray());
    }

    public function finalize(string $run, Request $request, PayrollRunGuardServiceContract $guard): JsonResponse
    {
        $result = $guard->assertCanFinalize($run, $request->all());
        if (! $result->allowed) {
            return response()->json($result->toArray(), 422);
        }

        return response()->json($result->toArray() + ['finalization_ready' => true]);
    }
}
