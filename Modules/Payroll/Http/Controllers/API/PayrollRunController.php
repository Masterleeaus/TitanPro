<?php

namespace Modules\Payroll\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollRunServiceContract;

class PayrollRunController extends Controller
{
    public function preview(Request $request, PayrollRunServiceContract $service): JsonResponse
    {
        $data = $request->validate(['company_id' => ['required', 'integer'], 'from' => ['required', 'date'], 'to' => ['required', 'date'], 'user_ids' => ['nullable', 'array']]);
        return response()->json(['data' => $service->preview((int) $data['company_id'], Carbon::parse($data['from']), Carbon::parse($data['to']), $data['user_ids'] ?? null)]);
    }
    public function run(Request $request, PayrollRunServiceContract $service): JsonResponse
    {
        $data = $request->validate(['company_id' => ['required', 'integer'], 'from' => ['required', 'date'], 'to' => ['required', 'date'], 'user_ids' => ['nullable', 'array'], 'options' => ['nullable', 'array']]);
        return response()->json(['data' => $service->run((int) $data['company_id'], Carbon::parse($data['from']), Carbon::parse($data['to']), $data['user_ids'] ?? null, $data['options'] ?? [])->toArray()], 201);
    }
}
