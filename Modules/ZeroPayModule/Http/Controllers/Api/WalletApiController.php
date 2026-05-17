<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;
use Modules\ZeroPayModule\Models\ZeroPayBankAccount;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class WalletApiController extends Controller
{
    /**
     * Return the authenticated user's wallet balance and receive info.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = $user->company_id ?? null;

        $balance = ZeroPayTransaction::withoutGlobalScope(TenantScope::class)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('net_amount');

        return response()->json([
            'balance' => (float) $balance,
            'currency' => 'AUD',
            'receive_info' => $this->buildReceiveInfo($companyId),
        ]);
    }

    /**
     * Return the static receive QR / PayID data for the authenticated user's company.
     */
    public function receiveInfo(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id ?? null;

        return response()->json($this->buildReceiveInfo($companyId));
    }

    private function buildReceiveInfo(?int $companyId): array
    {
        $bankAccount = null;

        if ($companyId) {
            // orderByDesc('is_default') sorts default accounts first (true=1 > false=0 in
            // DESC order), so the first result is always the preferred account.
            $bankAccount = ZeroPayBankAccount::query()
                ->where('company_id', $companyId)
                ->orderByDesc('is_default')
                ->first();
        }

        return [
            'pay_id' => $bankAccount?->pay_id,
            'account_name' => $bankAccount?->account_name,
            'bsb' => $bankAccount?->bsb,
            'account_number' => $bankAccount?->account_number,
            'bank_name' => $bankAccount?->bank_name,
        ];
    }
}
