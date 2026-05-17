<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Modules\ZeroPayModule\Models\ZeroPayBankAccount;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\ValueObjects\WebhookResult;

class BankTransferGateway extends AbstractGateway
{
    /**
     * @param  callable(int): ?array|null  $bankAccountResolver  Returns bank account details for a company ID, or null if none.
     * @param  callable(string): bool|null  $depositVerifier  Returns true when reference has a matched deposit.
     */
    public function __construct(
        ?array $config = null,
        protected mixed $bankAccountResolver = null,
        protected mixed $depositVerifier = null,
    ) {
        parent::__construct($config);
    }

    protected function gatewayKey(): string
    {
        return 'bank_transfer';
    }

    /**
     * Create a bank transfer payment: look up the default bank account for the company
     * and return the account details with a unique reference for manual matching.
     *
     * Status stays 'pending' until the matching engine confirms receipt.
     */
    public function createPayment(array $session): array
    {
        $companyId = $session['company_id'] ?? null;
        $reference = $session['session_token'] ?? uniqid('bt_', true);

        $bankAccount = $companyId !== null
            ? $this->resolveBankAccount((int) $companyId)
            : null;

        $data = ['reference' => $reference];

        if ($bankAccount !== null) {
            $data['bank_account'] = is_array($bankAccount) ? $bankAccount : [
                'account_name' => $bankAccount->account_name,
                'bsb' => $bankAccount->bsb,
                'account_number' => $bankAccount->account_number,
                'bank_name' => $bankAccount->bank_name,
            ];
        }

        return (new GatewayResponse(
            status: 'pending',
            gateway: 'bank_transfer',
            reference: $reference,
            data: $data,
        ))->toArray();
    }

    /**
     * Verify a bank transfer payment by checking zeropay_bank_deposits for a matched
     * record with the given reference.
     */
    public function verifyPayment(string $reference): array
    {
        $isMatched = $this->depositVerifier !== null
            ? (bool) ($this->depositVerifier)($reference)
            : (bool) ZeroPayBankDeposit::query()
                ->where('reference', $reference)
                ->where('status', 'matched')
                ->first();

        return (new GatewayResponse(
            status: $isMatched ? 'completed' : 'pending',
            gateway: 'bank_transfer',
            reference: $reference,
        ))->toArray();
    }

    /**
     * Bank transfers do not use webhooks; returns a no-op result.
     */
    public function handleWebhook(array $payload): array
    {
        return (new WebhookResult(
            processed: false,
            gateway: 'bank_transfer',
            payload: $payload,
        ))->toArray();
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'bank_transfer'];
    }

    private function resolveBankAccount(int $companyId): mixed
    {
        if ($this->bankAccountResolver !== null) {
            return ($this->bankAccountResolver)($companyId);
        }

        return ZeroPayBankAccount::query()
            ->where('company_id', $companyId)
            ->where('status', 'active')
            ->where('is_default', true)
            ->first();
    }
}
