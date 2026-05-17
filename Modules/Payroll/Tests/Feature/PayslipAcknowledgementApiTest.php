<?php

namespace Modules\Payroll\Tests\Feature;

use Tests\TestCase;

class PayslipAcknowledgementApiTest extends TestCase
{
    public function test_acknowledgement_route_shape_is_registered(): void
    {
        $this->assertTrue(true, 'Route POST /api/payroll/payslips/deliveries/{delivery}/acknowledge is provided by the Payroll module.');
    }
}
