<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Adapters\BankTransferGatewayAdapter;
use Modules\ZeroPayModule\Adapters\CashGatewayAdapter;
use Modules\ZeroPayModule\Adapters\CryptomusGatewayAdapter;
use Modules\ZeroPayModule\Adapters\PayIdGatewayAdapter;
use Modules\ZeroPayModule\Adapters\PayPalGatewayAdapter;
use Modules\ZeroPayModule\Adapters\StripeGatewayAdapter;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\QrCodeService;
use Modules\ZeroPayModule\Services\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\Services\ValueObjects\WebhookResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GatewayAdaptersTest extends TestCase
{
    private function makeSession(array $attributes = []): ZeroPaySession
    {
        $session = new ZeroPaySession;
        $session->setRawAttributes(array_merge([
            'id' => 1,
            'company_id' => 10,
            'session_token' => 'test-token-123',
            'amount' => '100.00',
            'currency' => 'AUD',
            'gateway' => 'payid',
            'status' => 'pending',
        ], $attributes));

        return $session;
    }

    public function test_stripe_adapter_returns_pending_response(): void
    {
        $adapter = new StripeGatewayAdapter;
        $session = $this->makeSession(['gateway' => 'stripe']);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('stripe', $adapter->getName());

        $result = $adapter->createPayment($session);

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertTrue($result->success);
        $this->assertSame('pending', $result->status);
        $this->assertNotEmpty($result->reference);
        $this->assertSame('stripe', $result->rawResponse['gateway']);
    }

    public function test_stripe_webhook_returns_webhook_result(): void
    {
        $adapter = new StripeGatewayAdapter;

        $result = $adapter->handleWebhook(['event' => 'payment_intent.succeeded']);

        $this->assertInstanceOf(WebhookResult::class, $result);
        $this->assertTrue($result->processed);
        $this->assertSame('processed', $result->status);
    }

    public function test_stripe_calculate_fee_applies_percentage_plus_fixed(): void
    {
        $adapter = new StripeGatewayAdapter;

        $fee = $adapter->calculateFee(100.0);

        $this->assertEqualsWithDelta(3.20, $fee, 0.01);
    }

    public function test_paypal_adapter_returns_pending_response(): void
    {
        $adapter = new PayPalGatewayAdapter;
        $session = $this->makeSession(['gateway' => 'paypal']);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('paypal', $adapter->getName());

        $result = $adapter->createPayment($session);

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertTrue($result->success);
        $this->assertSame('pending', $result->status);
    }

    public function test_paypal_webhook_returns_webhook_result(): void
    {
        $adapter = new PayPalGatewayAdapter;

        $result = $adapter->handleWebhook(['event_type' => 'PAYMENT.CAPTURE.COMPLETED']);

        $this->assertInstanceOf(WebhookResult::class, $result);
        $this->assertTrue($result->processed);
    }

    public function test_cryptomus_adapter_returns_pending_response(): void
    {
        $adapter = new CryptomusGatewayAdapter;
        $session = $this->makeSession(['gateway' => 'cryptomus']);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('cryptomus', $adapter->getName());

        $result = $adapter->createPayment($session);

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertTrue($result->success);
        $this->assertSame('pending', $result->status);
    }

    public function test_cash_adapter_marks_payment_pending(): void
    {
        $adapter = new CashGatewayAdapter;
        $session = $this->makeSession(['gateway' => 'cash']);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('cash', $adapter->getName());

        $result = $adapter->createPayment($session);

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertTrue($result->success);
        $this->assertSame('pending', $result->status);

        $verification = $adapter->verifyPayment($result->reference);

        $this->assertSame('pending', $verification->status);
    }

    public function test_payid_adapter_delegates_to_service_and_returns_pending(): void
    {
        /** @var QrCodeService&MockObject $qrCodeService */
        $qrCodeService = $this->createMock(QrCodeService::class);
        $qrCodeService->expects($this->never())->method('generateForSession');

        $adapter = new PayIdGatewayAdapter(
            qrCodeService: $qrCodeService,
            config: ['enabled' => true, 'pay_id' => 'pay@test.com', 'merchant_name' => 'Test'],
            transactionVerifier: fn (string $ref): bool => false,
        );

        $session = $this->makeSession(['id' => null, 'gateway' => 'payid']);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('payid', $adapter->getName());

        $result = $adapter->createPayment($session);

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertTrue($result->success);
        $this->assertSame('pending', $result->status);
        $this->assertNotEmpty($result->reference);
        $this->assertNotNull($result->redirectUrl);
    }

    public function test_payid_adapter_verify_returns_pending_when_not_completed(): void
    {
        /** @var QrCodeService&MockObject $qrCodeService */
        $qrCodeService = $this->createMock(QrCodeService::class);

        $adapter = new PayIdGatewayAdapter(
            qrCodeService: $qrCodeService,
            config: ['enabled' => true],
            transactionVerifier: fn (string $ref): bool => false,
        );

        $result = $adapter->verifyPayment('payid-ref-001');

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertSame('pending', $result->status);
        $this->assertSame('payid-ref-001', $result->reference);
    }

    public function test_payid_adapter_verify_returns_completed_when_verifier_true(): void
    {
        /** @var QrCodeService&MockObject $qrCodeService */
        $qrCodeService = $this->createMock(QrCodeService::class);

        $adapter = new PayIdGatewayAdapter(
            qrCodeService: $qrCodeService,
            config: ['enabled' => true],
            transactionVerifier: fn (string $ref): bool => true,
        );

        $result = $adapter->verifyPayment('payid-ref-done');

        $this->assertSame('completed', $result->status);
    }

    public function test_bank_transfer_adapter_returns_pending_without_bank_account(): void
    {
        $adapter = new BankTransferGatewayAdapter(
            config: ['enabled' => true],
            bankAccountResolver: fn (int $companyId): ?array => null,
            depositVerifier: fn (string $ref): bool => false,
        );

        $session = $this->makeSession(['gateway' => 'bank_transfer', 'session_token' => 'bt-tok-abc']);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('bank_transfer', $adapter->getName());

        $result = $adapter->createPayment($session);

        $this->assertInstanceOf(GatewayResponse::class, $result);
        $this->assertTrue($result->success);
        $this->assertSame('pending', $result->status);
        $this->assertSame('bt-tok-abc', $result->reference);
    }

    public function test_bank_transfer_adapter_verify_returns_completed_when_matched(): void
    {
        $adapter = new BankTransferGatewayAdapter(
            config: ['enabled' => true],
            depositVerifier: fn (string $ref): bool => true,
        );

        $result = $adapter->verifyPayment('bt-ref-matched');

        $this->assertSame('completed', $result->status);
        $this->assertSame('bt-ref-matched', $result->reference);
    }
}
