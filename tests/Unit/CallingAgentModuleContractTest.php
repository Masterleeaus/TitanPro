<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use ReflectionNamedType;
use Tests\TestCase;

class CallingAgentModuleContractTest extends TestCase
{
    #[Test]
    public function calling_agent_ai_tools_manifest_declares_resolvable_classes_with_array_execute_return_types(): void
    {
        $manifestPath = base_path('Modules/CallingAgent/manifests/ai_tools.json');
        $this->assertFileExists($manifestPath);

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        $this->assertIsArray($manifest);
        $this->assertIsArray($manifest['tools'] ?? null);

        foreach ($manifest['tools'] as $tool) {
            $class = $tool['class'] ?? null;
            $this->assertIsString($class);
            $this->assertTrue(class_exists($class), "Tool class {$class} must exist.");
            $this->assertTrue(method_exists($class, 'execute'), "Tool class {$class} must expose execute().");

            $reflection = new ReflectionMethod($class, 'execute');
            $returnType = $reflection->getReturnType();

            $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
            $this->assertSame('array', $returnType->getName(), "Tool {$class} execute() must return array.");
        }
    }

    #[Test]
    public function calling_agent_uses_canonical_database_migrations_path(): void
    {
        $providerPath = base_path('Modules/CallingAgent/Providers/ModuleServiceProvider.php');
        $providerSource = (string) file_get_contents($providerPath);

        $this->assertStringContainsString("Database/Migrations", $providerSource);
        $this->assertDirectoryExists(base_path('Modules/CallingAgent/Database/Migrations'));
        $this->assertDirectoryDoesNotExist(base_path('Modules/CallingAgent/Upgrade/Migrations'));

        $migrationFiles = glob(base_path('Modules/CallingAgent/Database/Migrations/*.php')) ?: [];
        $this->assertNotEmpty($migrationFiles, 'CallingAgent migrations should exist under Database/Migrations.');
    }
}
