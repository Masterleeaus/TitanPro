<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Contracts\Services\PayslipDeliveryAuditServiceContract;
use Modules\Payroll\Contracts\Services\PayslipDeliveryServiceContract;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class PayslipDeliveryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DB::table('payroll_payslip_deliveries')->latest('created_at');
        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json(['data' => $query->limit((int) $request->input('limit', 50))->get()]);
    }

    public function acknowledge(string $delivery, Request $request, PayslipDeliveryAuditServiceContract $audit): JsonResponse
    {
        $userId = (int) ($request->user()?->id ?? $request->input('user_id'));
        abort_if(! $userId, 422, 'Missing user context.');

        return response()->json(['data' => $audit->acknowledge($delivery, $userId, [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])]);
    }

    public function resend(Request $request, PayslipDeliveryServiceContract $delivery): JsonResponse
    {
        $data = $request->validate([
            'salary_slip_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'employee' => ['required', 'array'],
            'document' => ['nullable', 'array'],
            'options' => ['nullable', 'array'],
        ]);

        $document = new PayslipDocument(
            salarySlipId: (int) $data['salary_slip_id'],
            userId: (int) $data['user_id'],
            periodFrom: $data['document']['period_from'] ?? now()->startOfMonth()->toDateString(),
            periodTo: $data['document']['period_to'] ?? now()->endOfMonth()->toDateString(),
            gross: (float) ($data['document']['gross'] ?? 0),
            net: (float) ($data['document']['net'] ?? 0),
            currency: $data['document']['currency'] ?? config('payroll.currency', 'USD'),
            storagePath: $data['document']['storage_path'] ?? null,
            downloadUrl: $data['document']['download_url'] ?? null,
            meta: $data['document']['meta'] ?? [],
        );

        return response()->json(['data' => $delivery->deliver($document, $data['employee'], array_merge($data['options'] ?? [], ['resend' => true]))->toArray()]);
    }
}
