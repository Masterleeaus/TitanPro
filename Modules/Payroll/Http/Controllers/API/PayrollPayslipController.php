<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Actions\Reporting\GeneratePayslipAction;

class PayrollPayslipController extends Controller
{
    public function preview(Request $request, GeneratePayslipAction $action): JsonResponse
    {
        $data = $request->validate(['result' => ['required','array'], 'employee' => ['array'], 'company' => ['array']]);
        return response()->json($action->execute($data['result'], $data['employee'] ?? [], $data['company'] ?? []));
    }
}
