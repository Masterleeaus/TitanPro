<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class CashGateway extends AbstractGateway implements GatewayContract
{
    protected function gatewayKey(): string
    {
        return 'cash';
    }

    public function createPayment(array $session): array
    {
        $this->requireAvailability();

        $reference = $this->reference($session, 'cash_');

        return [
            'status' => 'pending',
            'gateway' => 'cash',
            'reference' => $reference,
            'session_reference' => $reference,
            'requires_admin_confirmation' => true,
            'message' => 'Cash payment pending admin confirmation.',
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return [
            'status' => 'pending',
            'gateway' => 'cash',
            'reference' => $reference,
            'requires_admin_confirmation' => true,
        ];
    }

    public function handleWebhook(array $payload): array
    {
        return [
            'processed' => true,
            'gateway' => 'cash',
            'status' => 'pending',
            'requires_admin_confirmation' => true,
            'payload' => $payload,
        ];
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'cash'];
    }
}
