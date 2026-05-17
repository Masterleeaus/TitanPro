<?php

namespace Modules\GroundZeroOps\Tests\Feature;

use Tests\TestCase;

class GroundZeroOpsApiRoutesTest extends TestCase
{
    public function test_assign_job_endpoint_requires_authentication(): void
    {
        $response = $this->postJson('/api/groundzero-ops/dispatch/assign', [
            'job_id' => 1,
            'technician_id' => 2,
        ]);

        $this->assertContains($response->getStatusCode(), [401, 302, 403]);
    }

    public function test_start_shift_endpoint_requires_authentication(): void
    {
        $response = $this->postJson('/api/groundzero-ops/shifts/start', [
            'technician_id' => 2,
        ]);

        $this->assertContains($response->getStatusCode(), [401, 302, 403]);
    }

    public function test_log_incident_endpoint_requires_authentication(): void
    {
        $response = $this->postJson('/api/groundzero-ops/incidents', [
            'details' => ['message' => 'test'],
        ]);

        $this->assertContains($response->getStatusCode(), [401, 302, 403]);
    }
}
