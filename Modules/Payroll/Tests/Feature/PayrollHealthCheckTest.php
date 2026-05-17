<?php

namespace Modules\Payroll\Tests\Feature;

use Modules\Payroll\Monitoring\Health\PayrollHealthCheck;
use Tests\TestCase;

class PayrollHealthCheckTest extends TestCase
{
    public function test_health_check_returns_status_payload(): void
    {
        $payload = (new PayrollHealthCheck())->check();

        $this->assertArrayHasKey('status', $payload);
        $this->assertArrayHasKey('missing_tables', $payload);
    }
}
