<?php

namespace Modules\GroundZeroOps\Tests\Contract;

use PHPUnit\Framework\TestCase;

class ManifestContractTest extends TestCase
{
    public function test_ai_tools_manifest_is_valid_and_declares_suggest_dispatch_tool(): void
    {
        $manifest = $this->loadManifest('ai_tools.json');

        $this->assertSame('GroundZeroOps', $manifest['module'] ?? null);
        $this->assertIsArray($manifest['tools'] ?? null);
        $this->assertNotEmpty($manifest['tools']);

        $tool = $manifest['tools'][0] ?? [];
        $this->assertSame('SuggestDispatchTool', $tool['name'] ?? null);
        $this->assertSame('low', $tool['risk_class'] ?? null);
        $this->assertSame('auto', $tool['approval_mode'] ?? null);
        $this->assertArrayHasKey('input_schema', $tool);
    }

    public function test_signals_manifest_is_valid_and_declares_required_signals(): void
    {
        $manifest = $this->loadManifest('signals_manifest.json');

        $this->assertContains('JobAssigned', $manifest['produced'] ?? []);
        $this->assertContains('ShiftStarted', $manifest['produced'] ?? []);
        $this->assertContains('ShiftEnded', $manifest['produced'] ?? []);
        $this->assertContains('IncidentLogged', $manifest['produced'] ?? []);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadManifest(string $file): array
    {
        $path = dirname(__DIR__, 2) . '/manifests/' . $file;

        $this->assertFileExists($path);

        $decoded = json_decode((string) file_get_contents($path), true);

        $this->assertIsArray($decoded);

        return $decoded;
    }
}
