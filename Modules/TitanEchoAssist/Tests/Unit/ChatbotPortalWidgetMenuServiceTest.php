<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\ChatbotPortalWidgetMenuService;
use PHPUnit\Framework\TestCase;

class ChatbotPortalWidgetMenuServiceTest extends TestCase
{
    public function test_menu_returns_widget_structure(): void
    {
        $menu = (new ChatbotPortalWidgetMenuService())->menu();

        $this->assertArrayHasKey('sections', $menu);
        $this->assertIsArray($menu['sections']);
        $this->assertNotEmpty($menu['sections']);

        foreach ($menu['sections'] as $section) {
            $this->assertArrayHasKey('key', $section);
            $this->assertArrayHasKey('items', $section);
            $this->assertIsArray($section['items']);

            foreach ($section['items'] as $item) {
                $this->assertArrayHasKey('key', $item);
                $this->assertArrayHasKey('label', $item);
                $this->assertArrayHasKey('path', $item);
            }
        }
    }
}
