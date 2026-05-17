<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\Contracts\GatewayContract;
use Modules\ZeroPayModule\Services\Gateways\PayIdGateway;
use Modules\ZeroPayModule\Services\QrCodeService;
use Modules\ZeroPayModule\Services\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\Services\ValueObjects\WebhookResult;

class PayIdGatewayAdapter implements GatewayContract
{
    private PayIdGateway $gateway;

    /**
     * @param  callable(string): bool|null  $transactionVerifier  Returns true when reference has a completed transaction.
     * @param  callable(string): void|null  $transactionUpdater  Marks pending transactions as completed for a reference.
     */
    public function __construct(
        QrCodeService $qrCodeService,
        ?array $config = null,
        mixed $transactionVerifier = null,
        mixed $transactionUpdater = null,
    ) {
        $this->gateway = new PayIdGateway(
            qrCodeService: $qrCodeService,
            config: $config,
            transactionVerifier: $transactionVerifier,
            transactionUpdater: $transactionUpdater,
        );
    }

    public function createPayment(ZeroPaySession $session): GatewayResponse
    {
        $result = $this->gateway->createPayment($session->toArray());

        return new GatewayResponse(
            success: true,
            reference: $result['reference'],
            status: $result['status'],
            rawResponse: $result,
            redirectUrl: $result['redirect_url'] ?? null,
        );
    }

    public function verifyPayment(string $reference): GatewayResponse
    {
        $result = $this->gateway->verifyPayment($reference);

        return new GatewayResponse(
            success: true,
            reference: $result['reference'],
            status: $result['status'],
            rawResponse: $result,
        );
    }

    public function handleWebhook(array $payload): WebhookResult
    {
        $result = $this->gateway->handleWebhook($payload);

        return new WebhookResult(
            processed: (bool) $result['processed'],
            status: $result['processed'] ? 'processed' : 'skipped',
            rawResponse: $result,
        );
    }

    public function calculateFee(float $amount): float
    {
        return $this->gateway->calculateFee($amount);
    }

    public function refundPayment(int $transactionId): GatewayResponse
    {
        $result = $this->gateway->refundPayment((string) $transactionId);

        return new GatewayResponse(
            success: true,
            reference: (string) $transactionId,
            status: $result['status'],
            rawResponse: $result,
        );
    }

    public function getName(): string
    {
        return 'payid';
    }

    public function isAvailable(): bool
    {
        return $this->gateway->isAvailable();
    }
}
