<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollPeriodLockServiceContract;

class PayrollPeriodLockController extends Controller
{
    public function lock(Request $request, PayrollPeriodLockServiceContract $locks): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'period_from' => ['required', 'date'],
            'period_to' => ['required', 'date'],
            'metadata' => ['sometimes', 'array'],
        ]);

        return response()->json($locks->lock((int) $data['company_id'], $data['period_from'], $data['period_to'], $data['metadata'] ?? []));
    }

    public function unlock(Request $request, PayrollPeriodLockServiceContract $locks): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'period_from' => ['required', 'date'],
            'period_to' => ['required', 'date'],
            'reason' => ['required', 'string', 'min:5'],
            'metadata' => ['sometimes', 'array'],
        ]);

        return response()->json($locks->unlock((int) $data['company_id'], $data['period_from'], $data['period_to'], $data['reason'], $data['metadata'] ?? []));
    }
}
