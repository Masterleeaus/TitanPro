<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Contracts\Services\EmployeeSelfServicePayrollContract;

class EmployeePayrollSelfServiceController
{
    public function payslips(Request $request, EmployeeSelfServicePayrollContract $service): JsonResponse
    {
        return response()->json($service->payslipsFor((int) $request->user()->id, $request->query()));
    }

    public function bankStatus(Request $request, EmployeeSelfServicePayrollContract $service): JsonResponse
    {
        return response()->json($service->bankDetailsStatus((int) $request->user()->id));
    }

    public function updateBank(Request $request, EmployeeSelfServicePayrollContract $service): JsonResponse
    {
        $payload = $request->validate([
            'account_name' => ['required', 'string', 'max:120'],
            'bsb' => ['nullable', 'string', 'max:20'],
            'account_number' => ['required', 'string', 'max:40'],
        ]);

        return response()->json($service->updateBankDetails((int) $request->user()->id, $payload), 202);
    }

    public function taxDeclaration(Request $request, EmployeeSelfServicePayrollContract $service): JsonResponse
    {
        return response()->json($service->taxDeclarationStatus((int) $request->user()->id));
    }
}
