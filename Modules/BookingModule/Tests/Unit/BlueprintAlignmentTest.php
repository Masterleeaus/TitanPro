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
}
