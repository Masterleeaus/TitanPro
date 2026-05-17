<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\Contracts\GatewayContract;
use Modules\ZeroPayModule\Services\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\Services\ValueObjects\WebhookResult;

class CashGatewayAdapter implements GatewayContract
{
    public function createPayment(ZeroPaySession $session): GatewayResponse
    {
        $reference = uniqid('cash_', true);

        return new GatewayResponse(
            success: true,
            reference: $reference,
            status: 'pending',
            rawResponse: ['gateway' => $this->getName(), 'session_id' => $session->id],
        );
    }

    public function verifyPayment(string $reference): GatewayResponse
    {
        return new GatewayResponse(
            success: true,
            reference: $reference,
            status: 'pending',
            rawResponse: ['gateway' => $this->getName()],
        );
    }

    public function handleWebhook(array $payload): WebhookResult
    {
        return new WebhookResult(
            processed: true,
            status: 'processed',
            rawResponse: ['gateway' => $this->getName(), 'payload' => $payload],
        );
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(int $transactionId): GatewayResponse
    {
        return new GatewayResponse(
            success: true,
            reference: (string) $transactionId,
            status: 'refunded',
            rawResponse: ['gateway' => $this->getName(), 'transaction_id' => $transactionId],
        );
    }

    public function getName(): string
    {
        return 'cash';
    }

    public function isAvailable(): bool
    {
        return true;
    }
}
