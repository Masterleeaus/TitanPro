<?php

namespace Modules\InstantAds\Tests\Contract;

use PHPUnit\Framework\TestCase;

class InstantAdsManifestContractTest extends TestCase
{
    public function test_required_manifest_files_exist_and_are_valid_json(): void
    {
        $base = dirname(__DIR__, 2) . '/manifests';

        foreach (['ai_tools.json', 'signals_manifest.json', 'api_manifest.json'] as $file) {
            $path = $base . '/' . $file;
            $this->assertFileExists($path);
            $this->assertIsArray(json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR));
        }
    }

    public function test_ai_tools_manifest_has_expected_tools_and_schema(): void
    {
        $manifest = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2) . '/manifests/ai_tools.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $toolsById = [];
        foreach ($manifest['tools'] as $tool) {
            $toolsById[$tool['id']] = $tool;
            $this->assertSame('low', $tool['risk_class']);
            $this->assertSame('auto', $tool['approval_mode']);
            $this->assertArrayHasKey('input_schema', $tool);
            $this->assertTrue(class_exists($tool['class']));
        }

        $this->assertArrayHasKey('generate_ad_image', $toolsById);
        $this->assertArrayHasKey('create_batch_variants', $toolsById);
    }

    public function test_signals_manifest_declares_ad_creative_generated_signal(): void
    {
        $manifest = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2) . '/manifests/signals_manifest.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $emits = array_column($manifest['emits'], 'signal');
        $this->assertContains('InstantAds::AdCreativeGenerated', $emits);
        $this->assertSame([], $manifest['consumes']);
    }
}
