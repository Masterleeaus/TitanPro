<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $transactions = ZeroPayTransaction::withoutGlobalScope(TenantScope::class)
            ->where('user_id', $userId)
            ->with('session.qrCodes')
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $transactions->map(fn ($tx) => $this->formatTransaction($tx)),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $transaction = ZeroPayTransaction::withoutGlobalScope(TenantScope::class)
            ->where('id', $id)
            ->where('user_id', $userId)
            ->with('session.qrCodes')
            ->firstOrFail();

        return response()->json($this->formatTransaction($transaction));
    }

    private function formatTransaction(ZeroPayTransaction $tx): array
    {
        $qrCode = $tx->session?->qrCodes?->first();
        $merchantName = $qrCode?->merchant_name ?? ($tx->meta['merchant_name'] ?? null);

        return [
            'id' => $tx->id,
            'type' => 'payment',
            'direction' => 'sent',
            'amount' => (float) $tx->amount,
            'currency' => $tx->currency,
            'fee' => (float) $tx->fee,
            'status' => $tx->status,
            'reference' => $tx->gateway_reference,
            'merchant_name' => $merchantName,
            'gateway' => $tx->gateway,
            'created_at' => $tx->created_at,
            'updated_at' => $tx->updated_at,
        ];
    }
}
