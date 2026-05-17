<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Contract test: validates that omni_manifest.json contains all 5 channels
 * with correct signature verification metadata per Blueprint 15 and 22.
 */
class OmniManifestTest extends TestCase
{
    private array $manifest;

    protected function setUp(): void
    {
        $path = __DIR__ . '/../../manifests/omni_manifest.json';
        $this->assertTrue(file_exists($path), 'omni_manifest.json must exist');

        $content = file_get_contents($path);
        $this->manifest = json_decode($content, true);
        $this->assertNotNull($this->manifest, 'omni_manifest.json must be valid JSON');
    }

    public function test_schema_is_correct(): void
    {
        $this->assertSame('titan.omni.manifest.v1', $this->manifest['schema']);
    }

    public function test_module_field_matches(): void
    {
        $this->assertSame('TitanEchoAssist', $this->manifest['module']);
    }

    public function test_all_five_channels_present(): void
    {
        $channelIds = array_column($this->manifest['channels'], 'id');

        $this->assertContains('whatsapp', $channelIds);
        $this->assertContains('telegram', $channelIds);
        $this->assertContains('messenger', $channelIds);
        $this->assertContains('voice', $channelIds);
        $this->assertContains('website', $channelIds);
    }

    public function test_channels_have_required_keys(): void
    {
        foreach ($this->manifest['channels'] as $channel) {
            $this->assertArrayHasKey('id', $channel);
            $this->assertArrayHasKey('label', $channel);
            $this->assertArrayHasKey('driver_class', $channel);
            $this->assertArrayHasKey('credential_scope', $channel);
            $this->assertArrayHasKey('enabled', $channel);
        }
    }

    public function test_credential_scope_is_company_id_for_all_channels(): void
    {
        foreach ($this->manifest['channels'] as $channel) {
            $this->assertSame(
                'company_id',
                $channel['credential_scope'],
                "Channel '{$channel['id']}' must scope credentials by company_id (Blueprint 22)"
            );
        }
    }

    public function test_whatsapp_uses_twilio_signature(): void
    {
        $channel = $this->findChannel('whatsapp');
        $this->assertNotNull($channel);
        $this->assertSame('X-Twilio-Signature', $channel['signature_header']);
        $this->assertStringContainsString('hmac', strtolower($channel['signature_algorithm']));
    }

    public function test_messenger_uses_hmac_sha256(): void
    {
        $channel = $this->findChannel('messenger');
        $this->assertNotNull($channel);
        $this->assertSame('X-Hub-Signature-256', $channel['signature_header']);
        $this->assertSame('hmac-sha256', $channel['signature_algorithm']);
    }

    public function test_telegram_uses_bearer_token(): void
    {
        $channel = $this->findChannel('telegram');
        $this->assertNotNull($channel);
        $this->assertSame('X-Telegram-Bot-Api-Secret-Token', $channel['signature_header']);
    }

    public function test_security_block_requires_webhook_signature(): void
    {
        $this->assertArrayHasKey('security', $this->manifest);
        $this->assertTrue($this->manifest['security']['webhook_signature_required']);
    }

    public function test_security_block_audits_failed_signatures(): void
    {
        $this->assertTrue($this->manifest['security']['audit_failed_signatures']);
    }

    public function test_security_secret_storage_is_per_company_id(): void
    {
        $this->assertSame('per_company_id', $this->manifest['security']['secret_storage']);
    }

    public function test_inbound_routing_is_enabled(): void
    {
        $this->assertArrayHasKey('inbound_routing', $this->manifest);
        $this->assertTrue($this->manifest['inbound_routing']['enabled']);
    }

    private function findChannel(string $id): ?array
    {
        foreach ($this->manifest['channels'] as $channel) {
            if ($channel['id'] === $id) {
                return $channel;
            }
        }
        return null;
    }
}
