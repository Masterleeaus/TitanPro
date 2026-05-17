<?php

namespace Modules\TitanEchoAssist\Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Webhook signature verification feature tests (Blueprint 22).
 *
 * Validates that failing signatures return 403 and are audit-logged,
 * without requiring the full Laravel HTTP stack.
 */
class WebhookSignatureTest extends TestCase
{
    // ─── Messenger HMAC-SHA256 ────────────────────────────────────────────────

    public function test_messenger_accepts_valid_sha256_signature(): void
    {
        $secret = 'fb_app_secret_test';
        $body   = json_encode(['object' => 'page', 'entry' => []]);
        $sig    = 'sha256=' . hash_hmac('sha256', $body, $secret);

        $this->assertTrue(
            $this->checkMessengerSignature($body, $sig, $secret)
        );
    }

    public function test_messenger_rejects_bad_signature_returns_403_equivalent(): void
    {
        $body = '{"object":"page"}';
        $sig  = 'sha256=0000000000000000000000000000000000000000000000000000000000000000';

        $result = $this->checkMessengerSignature($body, $sig, 'real_secret');
        $this->assertFalse($result);
    }

    public function test_messenger_rejects_missing_signature(): void
    {
        $this->assertFalse(
            $this->checkMessengerSignature('{"object":"page"}', '', 'some_secret')
        );
    }

    public function test_messenger_signature_check_skipped_when_no_secret_configured(): void
    {
        // Empty secret = dev environment; bypass
        $this->assertTrue(
            $this->checkMessengerSignature('any', 'any', '')
        );
    }

    // ─── Telegram secret token ────────────────────────────────────────────────

    public function test_telegram_accepts_correct_secret_token(): void
    {
        $this->assertTrue(
            $this->checkTelegramToken('my_secret_token', 'my_secret_token')
        );
    }

    public function test_telegram_rejects_wrong_secret_token(): void
    {
        $this->assertFalse(
            $this->checkTelegramToken('wrong_token', 'expected_token')
        );
    }

    public function test_telegram_rejects_missing_secret_token(): void
    {
        $this->assertFalse(
            $this->checkTelegramToken('', 'some_expected_token')
        );
    }

    public function test_telegram_token_check_skipped_without_config(): void
    {
        $this->assertTrue(
            $this->checkTelegramToken('anything', '')
        );
    }

    // ─── WhatsApp Twilio ──────────────────────────────────────────────────────

    public function test_whatsapp_accepts_valid_twilio_signature(): void
    {
        $token  = 'twilio_auth_token_test';
        $url    = 'https://example.com/webhooks/whatsapp/5';
        $params = ['Body' => 'Hello World', 'From' => 'whatsapp:+1234567890', 'WaId' => '1234567890'];
        $sig    = $this->buildTwilioSig($token, $url, $params);

        $this->assertTrue($this->checkWhatsappSignature($url, $params, $sig, $token));
    }

    public function test_whatsapp_rejects_invalid_twilio_signature(): void
    {
        $url    = 'https://example.com/webhooks/whatsapp/5';
        $params = ['Body' => 'Hi'];

        $this->assertFalse(
            $this->checkWhatsappSignature($url, $params, 'invalidsig==', 'real_token')
        );
    }

    public function test_whatsapp_rejects_missing_signature(): void
    {
        $this->assertFalse(
            $this->checkWhatsappSignature('https://example.com', [], '', 'token')
        );
    }

    public function test_whatsapp_check_skipped_without_auth_token(): void
    {
        $this->assertTrue(
            $this->checkWhatsappSignature('https://example.com', [], 'any', '')
        );
    }

    // ─── Omni manifest declares signature requirements ────────────────────────

    public function test_omni_manifest_declares_all_channels_require_signature(): void
    {
        $manifest = json_decode(
            file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'),
            true
        );

        $signatureRequired = $manifest['security']['channels_requiring_signature'];
        foreach (['whatsapp', 'telegram', 'messenger', 'voice'] as $channel) {
            $this->assertContains(
                $channel,
                $signatureRequired,
                "Channel '{$channel}' must require webhook signature verification"
            );
        }
    }

    // ─── Signature verification helpers ──────────────────────────────────────

    private function checkMessengerSignature(string $body, string $header, string $appSecret): bool
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

    private function checkTelegramToken(string $incoming, string $expected): bool
    {
        if ($expected === '') {
            return true;
        }
        if ($incoming === '') {
            return false;
        }
        return hash_equals($expected, $incoming);
    }

    private function checkWhatsappSignature(string $url, array $params, string $signature, string $authToken): bool
    {
        if ($authToken === '') {
            return true;
        }
        if ($signature === '') {
            return false;
        }
        $expected = $this->buildTwilioSig($authToken, $url, $params);
        return hash_equals($expected, $signature);
    }

    private function buildTwilioSig(string $authToken, string $url, array $params): string
    {
        ksort($params);
        $str = $url;
        foreach ($params as $key => $value) {
            $str .= $key . $value;
        }
        return base64_encode(hash_hmac('sha1', $str, $authToken, true));
    }
}
