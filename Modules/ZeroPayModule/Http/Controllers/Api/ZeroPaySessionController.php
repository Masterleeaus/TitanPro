<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\ZeroPayModule\Actions\CreateZeroPaySessionAction;
use Modules\ZeroPayModule\Actions\DeleteZeroPaySessionAction;
use Modules\ZeroPayModule\Actions\UpdateZeroPaySessionAction;
use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\GatewayFactory;
use Modules\ZeroPayModule\Services\PaymentSessionService;

class ZeroPaySessionController extends Controller
{
    public function __construct(
        protected CreateZeroPaySessionAction $createAction,
        protected UpdateZeroPaySessionAction $updateAction,
        protected DeleteZeroPaySessionAction $deleteAction,
        protected PaymentSessionService $sessionService,
        protected GatewayFactory $gatewayFactory,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id ?? null;

        if (! $companyId) {
            return response()->json(['error' => 'No company context for authenticated user'], 422);
        }

        $sessions = ZeroPaySession::query()
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(20);

        return response()->json($sessions);
    }

    public function store(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id ?? null;

        if (! $companyId) {
            return response()->json(['error' => 'No company context for authenticated user'], 422);
        }

        $supportedGateways = $this->gatewayFactory->supported();
        $validated = $request->validate([
            'gateway' => ['required', 'string', Rule::in($supportedGateways)],
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
        ]);

        $validated['company_id'] = $companyId;
        $validated['user_id'] = $request->user()->id;
        $validated['session_token'] = Str::uuid()->toString();
        $validated['name'] = $validated['session_token'];

        $session = $this->createAction->execute(ZeroPaySessionData::fromArray($validated));

        return response()->json($session, 201);
    }

    public function show(ZeroPaySession $session): JsonResponse
    {
        $session->load('transactions', 'qrCodes');

        $qrCode = $session->qrCodes->first();
        $qrPayload = [
            'payid' => $qrCode?->pay_id ?: null,
            'merchant_name' => $qrCode?->merchant_name ?: null,
            'amount' => $session->amount !== null ? (float) $session->amount : null,
            'currency' => $session->currency ?? 'AUD',
            'reference' => $qrCode?->reference ?? $session->session_token,
            'session_token' => $session->session_token,
            'expiry_timestamp' => $session->expires_at?->timestamp ?? 0,
        ];

        return response()->json(array_merge($session->toArray(), ['qr_payload' => $qrPayload]));
    }

    public function update(Request $request, ZeroPaySession $session): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'sometimes|string',
            'meta' => 'sometimes|array',
        ]);

        $data = array_merge($session->toArray(), $validated);
        $updated = $this->updateAction->execute($session, ZeroPaySessionData::fromArray($data));

        return response()->json($updated);
    }

    public function destroy(ZeroPaySession $session): JsonResponse
    {
        $this->deleteAction->execute($session);

        return response()->json(null, 204);
    }

    public function complete(Request $request, ZeroPaySession $session): JsonResponse
    {
        $validated = $request->validate(['reference' => 'required|string']);
        $transaction = $this->sessionService->complete($session, $validated['reference']);

        return response()->json($transaction);
    }

    public function cancel(ZeroPaySession $session): JsonResponse
    {
        $this->sessionService->cancel($session);

        return response()->json(['status' => 'cancelled']);
    }

    public function pay(Request $request, ZeroPaySession $session): JsonResponse
    {
        if ($session->expires_at && $session->expires_at->isPast()) {
            return response()->json(['error' => 'This payment session has expired.'], 422);
        }

        $supportedGateways = $this->gatewayFactory->supported();
        $validated = $request->validate([
            'gateway' => ['required', 'string', Rule::in($supportedGateways)],
        ]);

        $transaction = $this->sessionService->pay($session, $validated['gateway'], $request->user()->id);

        return response()->json([
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
        ], 201);
    }
}
