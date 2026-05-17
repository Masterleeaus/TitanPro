<?php

namespace Tests\Unit\TitanProAdmin;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TitanProAdminFilamentArchitectureTest extends TestCase
{
    #[Test]
    public function filament_pages_delegate_to_actions_or_services(): void
    {
        $files = [
            'AuditLogPage.php',
            'ModuleManagerPage.php',
            'PlatformHealthPage.php',
            'TenantConfigPage.php',
        ];

        foreach ($files as $file) {
            $path = dirname(__DIR__, 3)."/Modules/TitanProAdmin/Filament/Pages/{$file}";
            $content = file_get_contents($path);

            $this->assertTrue(
                str_contains((string) $content, 'Action::class')
                || str_contains((string) $content, 'Service::class'),
                "Expected {$file} to delegate to action/service classes."
            );
            $this->assertStringNotContainsString('::query()->create(', (string) $content);
            $this->assertStringNotContainsString('->forceFill(', (string) $content);
        }
    }
}
