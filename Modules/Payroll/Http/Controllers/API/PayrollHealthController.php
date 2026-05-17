<?php

namespace Modules\Payroll\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Payroll\Monitoring\Health\PayrollHealthCheck;

class PayrollHealthController extends Controller
{
    public function __invoke(PayrollHealthCheck $health): JsonResponse
    {
        return response()->json($health->check());
    }
}
