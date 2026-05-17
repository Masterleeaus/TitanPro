<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\Services\QrCodeService;
use Modules\ZeroPayModule\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\ValueObjects\WebhookResult;

class PayIdGateway extends AbstractGateway
{
    /**
     * @param  callable(string): bool|null  $transactionVerifier  Returns true when reference has a completed transaction.
     * @param  callable(string): void|null  $transactionUpdater  Marks pending transactions as completed for a reference.
     */
    public function __construct(
        protected QrCodeService $qrCodeService,
        ?array $config = null,
        protected mixed $transactionVerifier = null,
        protected mixed $transactionUpdater = null,
    ) {
        parent::__construct($config);
    }

    protected function gatewayKey(): string
    {
        return 'payid';
    }

    /**
     * Create a PayID payment: generate a QR payload, persist the zeropay_qr_codes record,
     * and return a redirect URL pointing to the payment page.
     */
    public function createPayment(array $session): array
    {
        $gatewayConfig = $this->gatewayConfig();
        $payId = (string) ($gatewayConfig['pay_id'] ?? 'payments@merchant.com');
        $merchantName = (string) ($gatewayConfig['merchant_name'] ?? 'ZeroPay Merchant');
        $reference = $session['session_token'] ?? uniqid('payid_', true);

        if (! empty($session['id'])) {
            $sessionModel = new ZeroPaySession;
            $sessionModel->setRawAttributes($session);

            $qrCode = $this->qrCodeService->generateForSession($sessionModel, $payId, $merchantName);
            $reference = $qrCode->reference;
        }

        $appUrl = rtrim((string) $this->configValue('app.url', 'http://localhost'), '/');

        return (new GatewayResponse(
            status: 'pending',
            gateway: 'payid',
            reference: $reference,
            data: [
                'pay_id' => $payId,
                'redirect_url' => $appUrl.'/pay/session/'.$reference,
            ],
        ))->toArray();
    }

    /**
     * Verify a PayID payment by checking zeropay_transactions for a completed record
     * matching the given reference.
     */
    public function verifyPayment(string $reference): array
    {
        $isCompleted = $this->transactionVerifier !== null
            ? (bool) ($this->transactionVerifier)($reference)
            : (bool) ZeroPayTransaction::query()
                ->where('gateway_reference', $reference)
                ->where('gateway', 'payid')
                ->where('status', 'completed')
                ->first();

        return (new GatewayResponse(
            status: $isCompleted ? 'completed' : 'pending',
            gateway: 'payid',
            reference: $reference,
        ))->toArray();
    }

    /**
     * Handle an NPP/PayID confirmation webhook.
     */
    public function handleWebhook(array $payload): array
    {
        $reference = $payload['reference'] ?? $payload['payid_reference'] ?? null;

        if ($reference) {
            if ($this->transactionUpdater !== null) {
                ($this->transactionUpdater)($reference);
            } else {
                ZeroPayTransaction::query()
                    ->where('gateway_reference', $reference)
                    ->where('gateway', 'payid')
                    ->where('status', 'pending')
                    ->update(['status' => 'completed']);
            }
        }

        return (new WebhookResult(
            processed: true,
            gateway: 'payid',
            payload: $payload,
        ))->toArray();
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'payid'];
    }
}
