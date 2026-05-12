<?php

namespace Modules\TitanEchoAssist\Tests\Contract;

use PHPUnit\Framework\TestCase;

/**
 * Schema-level contract tests for omni_manifest.json (Blueprint 15+17).
 *
 * Validates that all 5 channels are declared with correct security metadata.
 */
class OmniManifestSchemaTest extends TestCase
{
    private array $manifest;

    protected function setUp(): void
    {
        $path = __DIR__ . '/../../manifests/omni_manifest.json';
        $this->assertTrue(file_exists($path), 'omni_manifest.json must exist');

        $raw = file_get_contents($path);
        $this->assertJson($raw, 'omni_manifest.json must be valid JSON');

        $this->manifest = json_decode($raw, true);
    }

    public function test_schema_starts_with_titan_omni(): void
    {
        $this->assertStringStartsWith('titan.omni.', $this->manifest['schema']);
    }

    public function test_channels_is_non_empty_array(): void
    {
        $this->assertIsArray($this->manifest['channels']);
        $this->assertGreaterThanOrEqual(5, count($this->manifest['channels']));
    }

    public function test_each_channel_has_unique_id(): void
    {
        $ids = array_column($this->manifest['channels'], 'id');
        $this->assertSame(count($ids), count(array_unique($ids)), 'Channel IDs must be unique');
    }

    public function test_each_channel_has_required_fields(): void
    {
        $required = ['id', 'label', 'driver_class', 'credential_scope', 'enabled'];
        foreach ($this->manifest['channels'] as $channel) {
            foreach ($required as $field) {
                $this->assertArrayHasKey($field, $channel, "Channel must have '{$field}'");
            }
        }
    }

    public function test_each_channel_enabled_is_boolean(): void
    {
        foreach ($this->manifest['channels'] as $channel) {
            $this->assertIsBool($channel['enabled']);
        }
    }

    public function test_each_channel_driver_class_is_fqcn(): void
    {
        foreach ($this->manifest['channels'] as $channel) {
            $this->assertStringContainsString('\\', $channel['driver_class'], 'driver_class must be a FQCN');
        }
    }

    public function test_security_block_exists(): void
    {
        $this->assertArrayHasKey('security', $this->manifest);
    }

    public function test_security_webhook_signature_required_is_boolean(): void
    {
        $this->assertIsBool($this->manifest['security']['webhook_signature_required']);
    }

    public function test_security_channels_requiring_signature_is_array(): void
    {
        $this->assertIsArray($this->manifest['security']['channels_requiring_signature']);
    }

    public function test_auto_response_block_exists(): void
    {
        $this->assertArrayHasKey('auto_response', $this->manifest);
        $this->assertIsBool($this->manifest['auto_response']['enabled']);
    }

    public function test_inbound_routing_block_exists(): void
    {
        $this->assertArrayHasKey('inbound_routing', $this->manifest);
        $this->assertIsBool($this->manifest['inbound_routing']['enabled']);
    }

    public function test_five_canonical_channels_present(): void
    {
        $expected = ['whatsapp', 'telegram', 'messenger', 'voice', 'website'];
        $actual   = array_column($this->manifest['channels'], 'id');

        foreach ($expected as $ch) {
            $this->assertContains($ch, $actual, "Channel '{$ch}' must be in omni_manifest.json");
        }
    }

    public function test_channels_with_signature_have_signature_header(): void
    {
        $requiresSignature = $this->manifest['security']['channels_requiring_signature'];

        foreach ($this->manifest['channels'] as $channel) {
            if (in_array($channel['id'], $requiresSignature, true)) {
                $this->assertArrayHasKey(
                    'signature_header',
                    $channel,
                    "Channel '{$channel['id']}' requires signature but has no signature_header"
                );
                $this->assertNotNull($channel['signature_header']);
            }
        }
    }
}
