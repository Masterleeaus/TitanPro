<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\EmployeePayslipAccessTokenServiceContract;

class EmployeePayslipTokenController extends Controller
{
    public function issue(string $payslip, Request $request, EmployeePayslipAccessTokenServiceContract $tokens): JsonResponse
    {
        $employeeId = (int) ($request->user()->id ?? $request->input('employee_id'));
        return response()->json($tokens->issue($employeeId, $payslip, ['scope' => 'payslip:view']));
    }

    public function validateToken(string $payslip, Request $request, EmployeePayslipAccessTokenServiceContract $tokens): JsonResponse
    {
        return response()->json($tokens->validate((string) $request->input('token'), $payslip));
    }
}
