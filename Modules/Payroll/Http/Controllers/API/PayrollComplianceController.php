<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollComplianceServiceContract;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;

class PayrollComplianceController extends Controller
{
    public function __invoke(Request $request, PayrollComplianceServiceContract $compliance): JsonResponse
    {
        $data = $request->validate(['user_id' => ['required','integer'], 'gross_pay' => ['required','numeric'], 'net_pay' => ['required','numeric'], 'period_from' => ['nullable','date'], 'period_to' => ['nullable','date']]);
        $result = new PayrollCalculationResult((int) $data['user_id'], (float) $data['gross_pay'], (float) $data['net_pay'], (float) $data['gross_pay'], 0, 0, 0, [], [], [], (string) ($data['period_from'] ?? now()->startOfMonth()->toDateString()), (string) ($data['period_to'] ?? now()->endOfMonth()->toDateString()));
        return response()->json($compliance->inspect($result, $request->all())->toArray());
    }
}
