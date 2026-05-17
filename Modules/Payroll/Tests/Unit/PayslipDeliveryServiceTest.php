<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Notifications\PayslipDeliveryService;
use Modules\Payroll\Support\DTOs\PayslipDocument;
use PHPUnit\Framework\TestCase;

class PayslipDeliveryServiceTest extends TestCase
{
    public function test_delivery_fails_without_email(): void
    {
        $service = new PayslipDeliveryService();
        $document = new PayslipDocument(10, '2026-05-01', '2026-05-31', '<h1>Payslip</h1>', ['net_pay' => 1000]);

        $result = $service->deliver($document, [], ['send' => true]);

        $this->assertSame('failed', $result->status);
    }

    public function test_delivery_can_be_skipped(): void
    {
        $service = new PayslipDeliveryService();
        $document = new PayslipDocument(10, '2026-05-01', '2026-05-31', '<h1>Payslip</h1>', ['net_pay' => 1000]);

        $result = $service->deliver($document, ['email' => 'worker@example.test'], ['send' => false]);

        $this->assertSame('skipped', $result->status);
    }
}
