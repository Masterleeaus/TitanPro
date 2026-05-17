<?php

namespace Modules\Security\Tests\Feature;

use Tests\TestCase;
use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;

class SecurityOperationalReadinessTest extends TestCase
{
    public function test_readiness_service_returns_report_shape(): void
    {
        $report = app(OperationalReadinessServiceInterface::class)->report();

        $this->assertArrayHasKey('module', $report);
        $this->assertArrayHasKey('status', $report);
        $this->assertArrayHasKey('checks', $report);
    }
}
