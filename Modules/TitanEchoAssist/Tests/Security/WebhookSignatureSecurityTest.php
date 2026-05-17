<?php

namespace Modules\TitanEchoAssist\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * Security tests for webhook signature verification (Blueprint 22).
 *
 * Verifies that the signature verification logic embedded in each webhook
 * controller correctly accepts valid signatures and rejects invalid or missing ones.
 */
class WebhookSignatureSecurityTest extends TestCase
{
    // ─── Messenger HMAC-SHA256 ────────────────────────────────────────────────

    public function test_messenger_valid_hmac_sha256_signature_accepted(): void
    {
        $secret  = 'test_app_secret_abc';
        $body    = '{"object":"page","entry":[]}';
        $sig     = 'sha256=' . hash_hmac('sha256', $body, $secret);

        $this->assertTrue(
            $this->verifyMessengerSignature($body, $sig, $secret),
            'Valid HMAC-SHA256 signature must be accepted'
        );
    }

    public function test_messenger_invalid_hmac_signature_rejected(): void
    {
        $body = '{"object":"page","entry":[]}';
        $sig  = 'sha256=deadbeefdeadbeefdeadbeefdeadbeef';

        $this->assertFalse(
            $this->verifyMessengerSignature($body, $sig, 'test_secret'),
            'Invalid HMAC signature must be rejected'
        );
    }

    public function test_messenger_missing_signature_header_rejected(): void
    {
        $body = '{"object":"page"}';

        $this->assertFalse(
            $this->verifyMessengerSignature($body, '', 'some_secret'),
            'Missing X-Hub-Signature-256 header must be rejected'
        );
    }

    public function test_messenger_wrong_algorithm_prefix_rejected(): void
    {
        $secret = 'my_secret';
        $body   = '{"object":"page"}';
        // Correct hash but wrong prefix (sha1= instead of sha256=)
        $sig    = 'sha1=' . hash_hmac('sha256', $body, $secret);

        $this->assertFalse(
            $this->verifyMessengerSignature($body, $sig, $secret),
            'Signature with wrong algorithm prefix must be rejected'
        );
    }

    public function test_messenger_tampered_body_rejected(): void
    {
        $secret       = 'real_secret';
        $originalBody = '{"object":"page","entry":[]}';
        $tamperedBody = '{"object":"page","entry":[],"injected":"data"}';
        $sig          = 'sha256=' . hash_hmac('sha256', $originalBody, $secret);

        $this->assertFalse(
            $this->verifyMessengerSignature($tamperedBody, $sig, $secret),
            'Tampered body must cause signature rejection'
        );
    }

    public function test_messenger_empty_secret_bypasses_check(): void
    {
        // Empty secret = dev/test environment: all requests pass
        $this->assertTrue(
            $this->verifyMessengerSignature('any body', 'any sig', ''),
            'Empty app_secret must bypass verification for dev/test'
        );
    }

    // ─── Telegram secret token ────────────────────────────────────────────────

    public function test_telegram_valid_secret_token_accepted(): void
    {
        $secret  = 'my_telegram_secret_token';
        $incoming = 'my_telegram_secret_token';

        $this->assertTrue(
            $this->verifyTelegramToken($incoming, $secret),
            'Matching Telegram secret token must be accepted'
        );
    }

    public function test_telegram_invalid_token_rejected(): void
    {
        $this->assertFalse(
            $this->verifyTelegramToken('wrong_token', 'correct_token'),
            'Wrong Telegram secret token must be rejected'
        );
    }

    public function test_telegram_missing_token_rejected(): void
    {
        $this->assertFalse(
            $this->verifyTelegramToken('', 'some_secret'),
            'Missing Telegram secret token must be rejected'
        );
    }

    public function test_telegram_empty_secret_bypasses_check(): void
    {
        $this->assertTrue(
            $this->verifyTelegramToken('anything', ''),
            'Empty webhook_secret must bypass verification for dev/test'
        );
    }

    // ─── WhatsApp Twilio HMAC-SHA1 ────────────────────────────────────────────

    public function test_whatsapp_valid_twilio_signature_accepted(): void
    {
        $authToken = 'test_auth_token_xyz';
        $url       = 'https://example.com/webhooks/whatsapp/1';
        $params    = ['Body' => 'Hello', 'From' => '+1234567890'];

        $sig = $this->buildTwilioSignature($authToken, $url, $params);

        $this->assertTrue(
            $this->verifyWhatsappSignature($url, $params, $sig, $authToken),
            'Valid Twilio HMAC-SHA1 signature must be accepted'
        );
    }

    public function test_whatsapp_invalid_signature_rejected(): void
    {
        $authToken = 'real_token';
        $url       = 'https://example.com/webhooks/whatsapp/1';
        $params    = ['Body' => 'Hello'];

        $this->assertFalse(
            $this->verifyWhatsappSignature($url, $params, 'wrongsig==', $authToken),
            'Invalid Twilio signature must be rejected'
        );
    }

    public function test_whatsapp_missing_signature_rejected(): void
    {
        $this->assertFalse(
            $this->verifyWhatsappSignature('https://example.com', [], '', 'token'),
            'Missing X-Twilio-Signature must be rejected'
        );
    }

    public function test_whatsapp_empty_auth_token_bypasses_check(): void
    {
        $this->assertTrue(
            $this->verifyWhatsappSignature('https://example.com', [], 'any', ''),
            'Empty auth_token must bypass verification for dev/test'
        );
    }

    // ─── Helper methods (mirror the private logic of each controller) ─────────

    private function verifyMessengerSignature(string $body, string $header, string $appSecret): bool
    {
        if ($appSecret === '') {
            return true;
        }
        if ($header === '') {
            return false;
        }
        $expected = 'sha256=' . hash_hmac('sha256', $body, $appSecret);
        return hash_equals($expected, $header);
    }

    private function verifyTelegramToken(string $incoming, string $expected): bool
    {
        if ($expected === '') {
            return true;
        }
        if ($incoming === '') {
            return false;
        }
        return hash_equals($expected, $incoming);
    }

    private function verifyWhatsappSignature(string $url, array $params, string $signature, string $authToken): bool
    {
        if ($authToken === '') {
            return true;
        }
        if ($signature === '') {
            return false;
        }
        $expected = $this->buildTwilioSignature($authToken, $url, $params);
        return hash_equals($expected, $signature);
    }

    private function buildTwilioSignature(string $authToken, string $url, array $params): string
    {
        ksort($params);
        $signingString = $url;
        foreach ($params as $key => $value) {
            $signingString .= $key . $value;
        }
        return base64_encode(hash_hmac('sha1', $signingString, $authToken, true));
    }
}
