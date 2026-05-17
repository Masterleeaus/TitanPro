<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\ChatbotPortalWidgetMenuService;
use PHPUnit\Framework\TestCase;

class ChatbotPortalWidgetMenuServiceTest extends TestCase
{
    public function test_menu_returns_widget_structure(): void
    {
        $menu = (new ChatbotPortalWidgetMenuService())->menu();

        $this->assertArrayHasKey('hero', $menu);
        $this->assertArrayHasKey('sections', $menu);
        $this->assertIsArray($menu['sections']);
        $this->assertNotEmpty($menu['sections']);
    }
    public function test_builds_expected_menu_shape_with_customer_fixture(): void
    {
        $service = new ChatbotPortalWidgetMenuService();

        $menu = $service->build([
            'first_name' => 'Sarah',
            'customer_name' => 'Sarah Johnson',
            'customer_email' => 'sarah@example.com',
            'next_visit_label' => 'Thursday 22 May',
            'properties' => [['id' => 1, 'name' => 'Home', 'address' => '123 Main St']],
            'show_pay_invoice' => true,
            'show_approve_quote' => true,
            'show_request_reclean' => true,
            'show_rate_visit' => true,
        ]);

        $this->assertSame('Hi Sarah 👋', $menu['hero']['title']);
        $this->assertSame('Your next clean is Thursday 22 May', $menu['hero']['subtitle']);
        $this->assertSame(['Home', 'Chat', 'Help'], $menu['tabs']);
        $this->assertCount(8, $menu['sections']);
        $this->assertSame('Sarah Johnson', $menu['customer']['name']);
        $this->assertSame('sarah@example.com', $menu['customer']['email']);
    }

    public function test_filters_dynamic_actions_when_unavailable(): void
    {
        $service = new ChatbotPortalWidgetMenuService();

        $menu = $service->build([
            'first_name' => 'Sarah',
            'next_visit_label' => 'Thursday 22 May',
            'show_pay_invoice' => false,
            'show_approve_quote' => false,
            'show_request_reclean' => false,
            'show_rate_visit' => false,
        ]);

        $primaryValues = array_column($menu['hero']['primary_actions'], 'value');
        $allSectionValues = [];

        foreach ($menu['sections'] as $section) {
            $allSectionValues = array_merge($allSectionValues, array_column($section['items'], 'value'));
        }

        $this->assertNotContains('pay_invoice', $primaryValues);
        $this->assertNotContains('pay_invoice', $allSectionValues);
        $this->assertNotContains('approve_quote', $allSectionValues);
        $this->assertNotContains('request_reclean', $allSectionValues);
        $this->assertNotContains('rate_visit', $allSectionValues);
    }
}
