<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollTaxServiceContract;

class PayrollTaxController extends Controller
{
    public function __invoke(Request $request, PayrollTaxServiceContract $tax): JsonResponse
    {
        $data = $request->validate(['taxable_income' => ['required','numeric'], 'country' => ['nullable','string','size:2']]);
        return response()->json($tax->estimate((float) $data['taxable_income'], strtoupper($data['country'] ?? config('payroll.tax.default_country', 'AU'))));
    }
}
