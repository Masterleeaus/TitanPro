<?php

namespace Modules\TitanRewind\Tests\Contract;

use PHPUnit\Framework\TestCase;

class AiToolsManifestContractTest extends TestCase
{
    public function test_ai_tools_manifest_enforces_high_risk_and_required_approval(): void
    {
        $path = dirname(__DIR__, 2).'/manifests/ai_tools.json';
        $this->assertFileExists($path);

        $manifest = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('tools', $manifest);
        $this->assertNotEmpty($manifest['tools']);

        foreach ($manifest['tools'] as $tool) {
            $this->assertSame('high', $tool['risk_class'] ?? null);
            $this->assertSame('required', $tool['approval_mode'] ?? null);
        }
    }
}
