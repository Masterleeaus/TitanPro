<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class BlueprintAlignmentTest extends TestCase
{
    public function test_blueprint_manifests_exist(): void
    {
        $root = module_path('BookingModule');

        $this->assertFileExists($root . '/module.json');
        $this->assertFileExists($root . '/AI/Actions/action-map.json');
        $this->assertFileExists($root . '/AI/Control/control.manifest.json');
        $this->assertFileExists($root . '/Agents/ModuleAgent/agent.manifest.json');
        $this->assertFileExists($root . '/manifests/navigation.json');
    }

    public function test_module_json_has_single_filament_panel_entry(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'module.json'));

        $this->assertNotFalse($contents);
        $this->assertSame(1, substr_count($contents, '"filament_panel"'));

        $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('groundzero', $decoded['filament_panel'] ?? null);
    }
}
