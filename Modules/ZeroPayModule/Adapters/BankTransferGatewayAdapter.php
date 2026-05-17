<?php

namespace Modules\ZeroPayModule\Adapters;

use Illuminate\Contracts\Bus\Dispatcher;
use Modules\ZeroPayModule\Jobs\ProcessBankDepositJob;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\Contracts\GatewayContract;
use Modules\ZeroPayModule\Services\Gateways\BankTransferGateway;
use Modules\ZeroPayModule\Services\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\Services\ValueObjects\WebhookResult;

class BankTransferGatewayAdapter implements GatewayContract
{
    private BankTransferGateway $gateway;

    /**
     * @param  callable(int): ?array|null  $bankAccountResolver  Returns bank account details for a company ID, or null if none.
     * @param  callable(string): bool|null  $depositVerifier  Returns true when reference has a matched deposit.
     */
    public function __construct(
        ?array $config = null,
        mixed $bankAccountResolver = null,
        mixed $depositVerifier = null,
    ) {
        $this->gateway = new BankTransferGateway(
            config: $config,
            bankAccountResolver: $bankAccountResolver,
            depositVerifier: $depositVerifier,
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
        app(Dispatcher::class)->dispatch(new ProcessBankDepositJob($payload));

        return new WebhookResult(
            processed: true,
            status: 'processed',
            rawResponse: ['gateway' => $this->getName(), 'payload' => $payload, 'queued' => true],
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
        return 'bank_transfer';
    }

    public function isAvailable(): bool
    {
        return $this->gateway->isAvailable();
    }
}
