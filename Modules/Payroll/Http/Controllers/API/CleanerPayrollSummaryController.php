<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Services\Analytics\CleanerPayrollSummaryService;

class CleanerPayrollSummaryController
{
    public function __invoke(Request $request, CleanerPayrollSummaryService $service): JsonResponse
    {
        $runs = $request->input('runs', []);

        return response()->json([
            'data' => $service->summarize(is_array($runs) ? $runs : []),
        ]);
    }
}
