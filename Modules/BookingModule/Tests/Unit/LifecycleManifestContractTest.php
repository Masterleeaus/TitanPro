<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class LifecycleManifestContractTest extends TestCase
{
    public function test_lifecycle_manifest_declares_workflow_state_machine_contract(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'manifests/lifecycle.json'));

        $this->assertNotFalse($contents);
        $manifest = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $states = $manifest['workflow']['states'] ?? [];
        $this->assertContains('draft', $states);
        $this->assertContains('invoiced', $states);
        $this->assertContains('paid', $states);
    }
}

