<?php

namespace Modules\Payroll\Tests\Unit;

use Tests\TestCase;
use Modules\Payroll\Services\Security\PayslipAccessLinkService;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class PayslipAccessLinkServiceTest extends TestCase
{
    public function test_it_returns_existing_download_url_when_secure_links_are_disabled(): void
    {
        config(['payroll.features.secure_payslip_links' => false]);
        $service = new PayslipAccessLinkService();
        $document = new PayslipDocument(1, 10, '2026-05-01', '2026-05-31', 1000, 900, 'AUD', 'payslips/1.pdf', 'https://example.test/payslip.pdf');

        $this->assertSame('https://example.test/payslip.pdf', $service->temporaryLink($document));
    }
}
