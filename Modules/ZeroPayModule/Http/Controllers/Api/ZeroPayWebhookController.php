<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Models\ZeroPayWebhookEvent;
use Modules\ZeroPayModule\Services\GatewayFactory;

class ZeroPayWebhookController extends Controller
{
    public function __construct(protected GatewayFactory $gatewayFactory) {}

    public function stripe(Request $request): JsonResponse
    {
        return $this->handle($request, 'stripe');
    }

    public function paypal(Request $request): JsonResponse
    {
        return $this->handle($request, 'paypal');
    }

    public function cryptomus(Request $request): JsonResponse
    {
        return $this->handle($request, 'cryptomus');
    }

    public function bank(Request $request): JsonResponse
    {
        return $this->handle($request, 'bank_transfer');
    }

    public function handle(Request $request, string $gateway): JsonResponse
    {
        $supported = $this->gatewayFactory->supported();

        if (! in_array($gateway, $supported, true)) {
            return response()->json(['error' => 'Unsupported gateway'], 400);
        }

        $payload = $request->all();
        $signature = $request->header('X-Signature');

        // Always record the raw event before any processing so it is auditable.
        // The company_id from the payload is untrusted at this stage; it is
        // stored as-is for later reconciliation once the signature is verified.
        $event = ZeroPayWebhookEvent::create([
            'company_id' => isset($payload['company_id']) ? (int) $payload['company_id'] : 0,
            'gateway' => $gateway,
            'event_type' => $payload['event'] ?? 'unknown',
            'payload' => $payload,
            'signature' => $signature,
            'idempotency_key' => $payload['idempotency_key'] ?? null,
            'status' => 'received',
        ]);

        // Delegate the actual payload processing to the gateway adapter.
        // Each adapter is responsible for verifying the signature before
        // trusting any payload values (e.g. amount, company_id).
        $adapter = $this->gatewayFactory->make($gateway);
        $result = $adapter->handleWebhook($payload);

        $event->update(['status' => 'processed', 'processed_at' => now()]);

        return response()->json(['processed' => $result->processed, 'result' => $result->toArray()]);
    }
}
