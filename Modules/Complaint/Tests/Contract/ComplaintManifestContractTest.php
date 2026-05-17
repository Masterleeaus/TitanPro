<?php

namespace Modules\Complaint\Tests\Contract;

use PHPUnit\Framework\TestCase;

class ComplaintManifestContractTest extends TestCase
{
    public function test_ai_tools_manifest_schema_and_tools_are_valid(): void
    {
        $manifest = $this->loadManifest('ai_tools.json');

        $this->assertSame('titan.ai_tools.manifest.v1', $manifest['schema'] ?? null);
        $toolIds = array_column($manifest['tools'] ?? [], 'id');

        $this->assertContains('analyse_complaint', $toolIds);
        $this->assertContains('draft_resolution_response', $toolIds);

        foreach ($manifest['tools'] as $tool) {
            $this->assertSame('low', $tool['risk_class'] ?? null);
        }
    }

    public function test_signals_manifest_schema_and_signal_contract_are_valid(): void
    {
        $manifest = $this->loadManifest('signals_manifest.json');

        $this->assertSame('titan.signals.manifest.v1', $manifest['schema'] ?? null);
        $this->assertContains('ComplaintReceived', $manifest['produced'] ?? []);
        $this->assertContains('ComplaintEscalated', $manifest['produced'] ?? []);
        $this->assertContains('ComplaintResolved', $manifest['produced'] ?? []);
        $this->assertContains('ZeroFussPortal.FeedbackSubmitted', $manifest['consumed'] ?? []);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadManifest(string $file): array
    {
        $path = dirname(__DIR__, 2) . '/manifests/' . $file;

        $this->assertFileExists($path);

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }
}
