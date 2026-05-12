<?php

namespace Tests\Unit\Biometric;

use Filament\Contracts\Plugin;
use Modules\Biometric\Filament\Plugin\BiometricPlugin;
use PHPUnit\Framework\TestCase;

class BiometricMetadataIntegrationTest extends TestCase
{
    public function test_biometric_module_metadata_is_complete_and_targets_titango_panel(): void
    {
        $manifestPath = dirname(__DIR__, 3).'/Modules/Biometric/module.json';
        $manifest = json_decode((string) file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotSame('', $manifest['description'] ?? '');
        $this->assertIsArray($manifest['keywords'] ?? null);
        $this->assertNotEmpty($manifest['keywords'] ?? []);
        $this->assertSame('titango', $manifest['filament_panel'] ?? null);
        $this->assertSame(BiometricPlugin::class, $manifest['filament']['plugin'] ?? null);
    }

    public function test_biometric_filament_plugin_is_discoverable_and_contract_compliant(): void
    {
        $this->assertTrue(class_exists(BiometricPlugin::class));
        $this->assertTrue(is_a(BiometricPlugin::class, Plugin::class, true));
        $this->assertSame('biometric', BiometricPlugin::make()->getId());
    }
}
