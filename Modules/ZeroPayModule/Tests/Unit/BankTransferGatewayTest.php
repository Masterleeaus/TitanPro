<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Services\Gateways\BankTransferGateway;
use Modules\ZeroPayModule\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\ValueObjects\WebhookResult;
use PHPUnit\Framework\TestCase;

class BankTransferGatewayTest extends TestCase
{
    private BankTransferGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new BankTransferGateway(
            config: ['enabled' => true],
            bankAccountResolver: fn (int $companyId): ?array => null,
            depositVerifier: fn (string $ref): bool => false,
        );
    }

    public function test_create_payment_with_valid_session_returns_pending_response(): void
    {
        $session = [
            'id' => 1,
            'company_id' => 5,
            'session_token' => 'bt-session-abc',
            'amount' => '250.00',
            'currency' => 'AUD',
        ];

        $result = $this->gateway->createPayment($session);

        $this->assertSame('pending', $result['status']);
        $this->assertSame('bank_transfer', $result['gateway']);
        $this->assertSame('bt-session-abc', $result['reference']);
    }

    public function test_create_payment_without_session_token_generates_reference(): void
    {
        $session = [
            'id' => 2,
            'company_id' => 3,
            'amount' => '50.00',
        ];

        $result = $this->gateway->createPayment($session);

        $this->assertSame('pending', $result['status']);
        $this->assertSame('bank_transfer', $result['gateway']);
        $this->assertNotEmpty($result['reference']);
    }

    public function test_create_payment_includes_bank_account_when_resolver_returns_details(): void
    {
        $bankDetails = [
            'account_name' => 'ZeroPay Pty Ltd',
            'bsb' => '123-456',
            'account_number' => '98765432',
            'bank_name' => 'ANZ',
        ];

        $gateway = new BankTransferGateway(
            config: ['enabled' => true],
            bankAccountResolver: fn (int $companyId): ?array => $bankDetails,
        );

        $result = $gateway->createPayment([
            'id' => 1,
            'company_id' => 7,
            'session_token' => 'ref-xyz',
        ]);

        $this->assertSame('ref-xyz', $result['reference']);
        $this->assertArrayHasKey('bank_account', $result);
        $this->assertSame('ZeroPay Pty Ltd', $result['bank_account']['account_name']);
        $this->assertSame('123-456', $result['bank_account']['bsb']);
    }

    public function test_create_payment_omits_bank_account_when_resolver_returns_null(): void
    {
        $result = $this->gateway->createPayment([
            'id' => 1,
            'company_id' => 99,
            'session_token' => 'tok',
        ]);

        $this->assertArrayNotHasKey('bank_account', $result);
    }

    public function test_verify_payment_returns_pending_when_deposit_not_matched(): void
    {
        $result = $this->gateway->verifyPayment('bt-ref-unknown');

        $this->assertSame('bank_transfer', $result['gateway']);
        $this->assertSame('bt-ref-unknown', $result['reference']);
        $this->assertSame('pending', $result['status']);
    }

    public function test_verify_payment_returns_completed_when_deposit_matched(): void
    {
        $gateway = new BankTransferGateway(
            config: ['enabled' => true],
            depositVerifier: fn (string $ref): bool => true,
        );

        $result = $gateway->verifyPayment('bt-ref-matched');

        $this->assertSame('completed', $result['status']);
        $this->assertSame('bank_transfer', $result['gateway']);
    }

    public function test_handle_webhook_returns_not_processed(): void
    {
        $payload = ['event' => 'bank.deposit'];

        $result = $this->gateway->handleWebhook($payload);

        $this->assertFalse($result['processed']);
        $this->assertSame('bank_transfer', $result['gateway']);
        $this->assertSame($payload, $result['payload']);
    }

    public function test_calculate_fee_returns_zero(): void
    {
        $this->assertSame(0.0, $this->gateway->calculateFee(500.0));
        $this->assertSame(0.0, $this->gateway->calculateFee(0.0));
    }

    public function test_refund_payment_returns_refunded_status(): void
    {
        $result = $this->gateway->refundPayment('txn-bt-001');

        $this->assertSame('refunded', $result['status']);
        $this->assertSame('txn-bt-001', $result['transaction_id']);
        $this->assertSame('bank_transfer', $result['gateway']);
    }

    public function test_gateway_response_value_object_merges_data(): void
    {
        $response = new GatewayResponse(
            status: 'pending',
            gateway: 'bank_transfer',
            reference: 'bt-ref-001',
            data: [
                'bank_account' => [
                    'account_name' => 'ZeroPay Pty Ltd',
                    'bsb' => '123-456',
                    'account_number' => '98765432',
                    'bank_name' => 'ANZ',
                ],
                'reference' => 'bt-ref-001',
            ],
        );

        $array = $response->toArray();

        $this->assertSame('pending', $array['status']);
        $this->assertSame('bank_transfer', $array['gateway']);
        $this->assertSame('bt-ref-001', $array['reference']);
        $this->assertArrayHasKey('bank_account', $array);
        $this->assertSame('ZeroPay Pty Ltd', $array['bank_account']['account_name']);
    }

    public function test_webhook_result_not_processed_for_bank_transfer(): void
    {
        $result = new WebhookResult(
            processed: false,
            gateway: 'bank_transfer',
            payload: [],
        );

        $array = $result->toArray();

        $this->assertFalse($array['processed']);
        $this->assertSame('bank_transfer', $array['gateway']);
    }
}
