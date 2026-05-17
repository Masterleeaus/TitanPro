<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollVarianceServiceContract;

class PayrollVarianceController extends Controller
{
    public function __invoke(Request $request, PayrollVarianceServiceContract $variance): JsonResponse
    {
        $data = $request->validate([
            'current_run' => ['required', 'array'],
            'previous_run' => ['sometimes', 'nullable', 'array'],
            'options' => ['sometimes', 'array'],
        ]);

        return response()->json($variance->compare($data['current_run'], $data['previous_run'] ?? null, $data['options'] ?? []));
    }
}
