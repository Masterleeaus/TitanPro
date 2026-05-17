<?php

namespace Modules\ZeroPayModule\Services\Contracts;

use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\Services\ValueObjects\WebhookResult;

interface GatewayContract
{
    public function createPayment(ZeroPaySession $session): GatewayResponse;

    public function verifyPayment(string $reference): GatewayResponse;

    public function handleWebhook(array $payload): WebhookResult;

    public function calculateFee(float $amount): float;

    public function refundPayment(int $transactionId): GatewayResponse;

    public function getName(): string;

    public function isAvailable(): bool;
}
