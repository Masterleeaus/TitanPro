<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\CleaningPayrollServiceContract;
use Modules\Payroll\Support\DTOs\CleaningPayrollInput;

class CleaningPayrollController extends Controller
{
    public function preview(Request $request, CleaningPayrollServiceContract $service): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'is_contractor' => ['sometimes', 'boolean'],
            'shifts' => ['required', 'array', 'min:1'],
            'shifts.*.site_code' => ['nullable', 'string'],
            'shifts.*.started_at' => ['required'],
            'shifts.*.ended_at' => ['required'],
            'shifts.*.hourly_rate' => ['required', 'numeric', 'min:0'],
            'shifts.*.is_approved' => ['sometimes', 'boolean'],
            'shifts.*.is_public_holiday' => ['sometimes', 'boolean'],
            'shifts.*.requires_travel_allowance' => ['sometimes', 'boolean'],
            'shifts.*.requires_site_allowance' => ['sometimes', 'boolean'],
            'shifts.*.requires_equipment_allowance' => ['sometimes', 'boolean'],
            'extra_earnings' => ['sometimes', 'array'],
            'deductions' => ['sometimes', 'array'],
            'metadata' => ['sometimes', 'array'],
        ]);

        $result = $service->calculate(new CleaningPayrollInput(
            companyId: (int) $data['company_id'],
            userId: (int) $data['user_id'],
            shifts: $data['shifts'],
            isContractor: (bool) ($data['is_contractor'] ?? false),
            extraEarnings: (array) ($data['extra_earnings'] ?? []),
            deductions: (array) ($data['deductions'] ?? []),
            metadata: (array) ($data['metadata'] ?? []),
        ));

        return response()->json(['data' => $result->toArray()]);
    }

    public function variance(Request $request, CleaningPayrollServiceContract $service): JsonResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'shifts' => ['required', 'array', 'min:1'],
        ]);

        return response()->json([
            'data' => $service->inspectRosterVariance(new CleaningPayrollInput((int) $data['company_id'], (int) $data['user_id'], $data['shifts'])),
        ]);
    }
}
