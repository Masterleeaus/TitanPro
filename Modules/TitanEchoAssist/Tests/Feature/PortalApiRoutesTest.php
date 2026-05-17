<?php

namespace Modules\TitanEchoAssist\Tests\Feature;

use PHPUnit\Framework\TestCase;

class PortalApiRoutesTest extends TestCase
{
    private string $routesFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routesFile = __DIR__ . '/../../Routes/api.php';
    }

    public function test_portal_route_group_uses_session_validation_and_rate_limit(): void
    {
        $content = file_get_contents($this->routesFile);

        $this->assertStringContainsString("ValidatePortalSessionToken::class", $content);
        $this->assertStringContainsString("throttle:60,1", $content);
        $this->assertStringContainsString("prefix('/{chatbot:uuid}/session/{sessionId}')", $content);
    }

    public function test_portal_core_endpoints_are_defined(): void
    {
        $content = file_get_contents($this->routesFile);

        foreach ([
            '/conversation',
            '/conversation/{id}/messages',
            '/conversation/{id}/file',
            '/send-email',
            '/review',
            '/portal/home',
            '/portal/menu',
            '/portal/dashboard',
            '/portal/bookings',
            '/portal/visits',
            '/portal/invoices',
            '/portal/documents',
            '/portal/issues',
            '/portal/actions',
            '/portal/recurring/{customerId}',
            '/portal/notifications/{customerId}',
            '/portal/site-profiles',
            '/portal/sites/{siteId}',
            '/portal/feedback',
        ] as $uri) {
            $this->assertStringContainsString($uri, $content, "Missing route URI: {$uri}");
        }
    }
}
