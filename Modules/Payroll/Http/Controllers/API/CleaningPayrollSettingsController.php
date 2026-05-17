<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Contracts\Services\CleaningPayrollSettingsServiceContract;

class CleaningPayrollSettingsController
{
    public function show(Request $request, CleaningPayrollSettingsServiceContract $settings): JsonResponse
    {
        return response()->json($settings->defaults($request->user()?->company_id));
    }

    public function validate(Request $request, CleaningPayrollSettingsServiceContract $settings): JsonResponse
    {
        return response()->json($settings->normalize($request->all(), $request->user()?->company_id));
    }
}
