<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use chillerlan\QRCode\QRCode;
use Illuminate\Support\Carbon;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\QrPayloadBuilder;
use Modules\ZeroPayModule\Services\QrPayloadValidator;
use Modules\ZeroPayModule\ValueObjects\QrPayload;
use PHPUnit\Framework\TestCase;

class QrPayloadBuilderTest extends TestCase
{
    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadBuilder::build()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_build_produces_spec_compliant_payload(): void
    {
        $expiry = Carbon::createFromTimestamp(1_900_000_000);
        $session = new ZeroPaySession([
            'session_token' => 'abc123xyz',
            'amount' => 99.95,
            'currency' => 'AUD',
            'meta' => [
                'payid' => 'merchant@zeropay.io',
                'merchant_name' => 'Acme Pty Ltd',
                'reference' => 'INV-2026-001',
            ],
            'expires_at' => $expiry,
        ]);

        $builder = new QrPayloadBuilder;
        $payload = $builder->build($session);

        $this->assertInstanceOf(QrPayload::class, $payload);
        $this->assertSame('merchant@zeropay.io', $payload->payid);
        $this->assertSame('Acme Pty Ltd', $payload->merchantName);
        $this->assertSame(99.95, $payload->amount);
        $this->assertSame('AUD', $payload->currency);
        $this->assertSame('INV-2026-001', $payload->reference);
        $this->assertSame('abc123xyz', $payload->sessionToken);
        $this->assertSame($expiry->getTimestamp(), $payload->expiryTimestamp);
    }

    public function test_build_uses_constructor_defaults_when_meta_is_absent(): void
    {
        $session = new ZeroPaySession([
            'session_token' => 'tok-001',
            'amount' => null,
            'currency' => null,
        ]);

        $builder = new QrPayloadBuilder(
            defaultPayId: 'default@zeropay.io',
            defaultMerchantName: 'Default Merchant',
        );
        $payload = $builder->build($session);

        $this->assertSame('default@zeropay.io', $payload->payid);
        $this->assertSame('Default Merchant', $payload->merchantName);
        $this->assertNull($payload->amount);
        $this->assertSame('AUD', $payload->currency);
        $this->assertSame('tok-001', $payload->reference);   // falls back to session_token
        $this->assertSame('tok-001', $payload->sessionToken);
    }

    public function test_build_uses_ttl_when_session_has_no_expiry(): void
    {
        $before = time();
        $session = new ZeroPaySession([
            'session_token' => 'tok-002',
        ]);

        $builder = new QrPayloadBuilder(sessionTtlMinutes: 15);
        $payload = $builder->build($session);
        $after = time();

        // expiryTimestamp should be approximately now + 15 minutes
        $this->assertGreaterThanOrEqual($before + 15 * 60, $payload->expiryTimestamp);
        $this->assertLessThanOrEqual($after + 15 * 60, $payload->expiryTimestamp);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadBuilder::buildFallbackUrl()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_fallback_url_follows_spec_format(): void
    {
        $builder = new QrPayloadBuilder;

        $this->assertSame('/pay/session/abc123xyz', $builder->buildFallbackUrl('abc123xyz'));
        $this->assertSame('/pay/session/tok-999', $builder->buildFallbackUrl('tok-999'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadBuilder::toJson()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_to_json_produces_valid_spec_compliant_json(): void
    {
        $payload = new QrPayload;
        $payload->payid = 'merchant@zeropay.io';
        $payload->merchantName = 'Acme Pty Ltd';
        $payload->amount = 99.95;
        $payload->currency = 'AUD';
        $payload->reference = 'INV-2026-001';
        $payload->sessionToken = 'abc123xyz';
        $payload->expiryTimestamp = 1_234_567_890;

        $builder = new QrPayloadBuilder;
        $json = $builder->toJson($payload);

        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertSame('merchant@zeropay.io', $decoded['payid']);
        $this->assertSame('Acme Pty Ltd', $decoded['merchant_name']);
        $this->assertSame(99.95, $decoded['amount']);
        $this->assertSame('AUD', $decoded['currency']);
        $this->assertSame('INV-2026-001', $decoded['reference']);
        $this->assertSame('abc123xyz', $decoded['session_token']);
        $this->assertSame(1_234_567_890, $decoded['expiry_timestamp']);
    }

    public function test_to_json_handles_null_amount(): void
    {
        $payload = new QrPayload;
        $payload->payid = 'p@example.com';
        $payload->merchantName = 'Shop';
        $payload->amount = null;
        $payload->currency = 'AUD';
        $payload->reference = 'ref';
        $payload->sessionToken = 'tok';
        $payload->expiryTimestamp = 9_999_999_999;

        $json = (new QrPayloadBuilder)->toJson($payload);
        $decoded = json_decode($json, true);

        $this->assertNull($decoded['amount']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadBuilder::toQrImage()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_to_qr_image_calls_injected_renderer_with_json_and_size(): void
    {
        $capturedContent = null;
        $capturedSize = null;

        $renderer = function (string $content, int $size) use (&$capturedContent, &$capturedSize): string {
            $capturedContent = $content;
            $capturedSize = $size;

            return base64_encode('fake-png-data');
        };

        $payload = new QrPayload;
        $payload->payid = 'p@example.com';
        $payload->merchantName = 'Shop';
        $payload->amount = 10.0;
        $payload->currency = 'AUD';
        $payload->reference = 'ref';
        $payload->sessionToken = 'tok';
        $payload->expiryTimestamp = 9_999_999_999;

        $builder = new QrPayloadBuilder(qrRenderer: $renderer);
        $result = $builder->toQrImage($payload, 300);

        $this->assertSame(base64_encode('fake-png-data'), $result);
        $this->assertSame(300, $capturedSize);

        $decoded = json_decode($capturedContent, true);
        $this->assertSame('tok', $decoded['session_token']);
    }

    public function test_to_qr_image_uses_default_size_of_400(): void
    {
        $capturedSize = null;
        $renderer = static function (string $content, int $size) use (&$capturedSize): string {
            $capturedSize = $size;

            return base64_encode('png');
        };

        $payload = new QrPayload;
        $payload->payid = 'p@e.com';
        $payload->merchantName = 'M';
        $payload->amount = null;
        $payload->currency = 'AUD';
        $payload->reference = 'r';
        $payload->sessionToken = 't';
        $payload->expiryTimestamp = 1;

        (new QrPayloadBuilder(qrRenderer: $renderer))->toQrImage($payload);

        $this->assertSame(400, $capturedSize);
    }

    public function test_to_qr_image_throws_when_no_renderer_and_library_absent(): void
    {
        // Only run this assertion when chillerlan/php-qrcode is NOT installed
        if (class_exists(QRCode::class)) {
            $this->markTestSkipped('chillerlan/php-qrcode is installed; skipping missing-library test.');
        }

        $payload = new QrPayload;
        $payload->payid = 'p@e.com';
        $payload->merchantName = 'M';
        $payload->amount = null;
        $payload->currency = 'AUD';
        $payload->reference = 'r';
        $payload->sessionToken = 't';
        $payload->expiryTimestamp = 1;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/chillerlan\/php-qrcode/');

        (new QrPayloadBuilder)->toQrImage($payload);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadValidator::validate()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_validate_parses_valid_json_into_payload(): void
    {
        $json = json_encode([
            'payid' => 'merchant@zeropay.io',
            'merchant_name' => 'Acme Pty Ltd',
            'amount' => 99.95,
            'currency' => 'AUD',
            'reference' => 'INV-2026-001',
            'session_token' => 'abc123xyz',
            'expiry_timestamp' => 1_234_567_890,
        ]);

        $validator = new QrPayloadValidator;
        $payload = $validator->validate($json);

        $this->assertSame('merchant@zeropay.io', $payload->payid);
        $this->assertSame('Acme Pty Ltd', $payload->merchantName);
        $this->assertSame(99.95, $payload->amount);
        $this->assertSame('AUD', $payload->currency);
        $this->assertSame('INV-2026-001', $payload->reference);
        $this->assertSame('abc123xyz', $payload->sessionToken);
        $this->assertSame(1_234_567_890, $payload->expiryTimestamp);
    }

    public function test_validate_accepts_null_amount(): void
    {
        $json = json_encode([
            'payid' => 'p@e.com',
            'merchant_name' => 'Shop',
            'amount' => null,
            'currency' => 'AUD',
            'reference' => 'ref',
            'session_token' => 'tok',
            'expiry_timestamp' => 9_999_999_999,
        ]);

        $payload = (new QrPayloadValidator)->validate($json);

        $this->assertNull($payload->amount);
    }

    public function test_validate_throws_on_invalid_json(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/not valid JSON/');

        (new QrPayloadValidator)->validate('not-json');
    }

    public function test_validate_throws_on_missing_required_field(): void
    {
        $json = json_encode([
            'payid' => 'p@e.com',
            // 'merchant_name' missing
            'currency' => 'AUD',
            'reference' => 'ref',
            'session_token' => 'tok',
            'expiry_timestamp' => 1,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/merchant_name/');

        (new QrPayloadValidator)->validate($json);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadValidator::isExpired()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_is_expired_returns_false_for_future_timestamp(): void
    {
        $payload = new QrPayload;
        $payload->expiryTimestamp = time() + 60;

        $this->assertFalse((new QrPayloadValidator)->isExpired($payload));
    }

    public function test_is_expired_returns_true_for_past_timestamp(): void
    {
        $payload = new QrPayload;
        $payload->expiryTimestamp = time() - 1;

        $this->assertTrue((new QrPayloadValidator)->isExpired($payload));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // QrPayloadValidator::matchesSession()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_matches_session_returns_true_when_tokens_match(): void
    {
        $session = new ZeroPaySession(['session_token' => 'abc123xyz']);

        $payload = new QrPayload;
        $payload->sessionToken = 'abc123xyz';

        $this->assertTrue((new QrPayloadValidator)->matchesSession($payload, $session));
    }

    public function test_matches_session_returns_false_when_tokens_differ(): void
    {
        $session = new ZeroPaySession(['session_token' => 'abc123xyz']);

        $payload = new QrPayload;
        $payload->sessionToken = 'different-token';

        $this->assertFalse((new QrPayloadValidator)->matchesSession($payload, $session));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Round-trip: build → toJson → validate
    // ──────────────────────────────────────────────────────────────────────────

    public function test_round_trip_build_to_json_to_validate(): void
    {
        $expiry = Carbon::createFromTimestamp(2_000_000_000);
        $session = new ZeroPaySession([
            'session_token' => 'round-trip-tok',
            'amount' => 50.00,
            'currency' => 'AUD',
            'meta' => [
                'payid' => 'rt@zeropay.io',
                'merchant_name' => 'RT Merchant',
                'reference' => 'RT-REF-001',
            ],
            'expires_at' => $expiry,
        ]);

        $builder = new QrPayloadBuilder;
        $validator = new QrPayloadValidator;

        $built = $builder->build($session);
        $json = $builder->toJson($built);
        $validated = $validator->validate($json);

        $this->assertSame($built->payid, $validated->payid);
        $this->assertSame($built->merchantName, $validated->merchantName);
        $this->assertSame($built->amount, $validated->amount);
        $this->assertSame($built->currency, $validated->currency);
        $this->assertSame($built->reference, $validated->reference);
        $this->assertSame($built->sessionToken, $validated->sessionToken);
        $this->assertSame($built->expiryTimestamp, $validated->expiryTimestamp);
        $this->assertFalse($validator->isExpired($validated));
    }
}
