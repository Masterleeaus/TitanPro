<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class VerticalDocumentationTest extends TestCase
{
    public function test_vertical_readme_documents_all_supported_verticals(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Verticals/README.md'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString('services', $contents);
        $this->assertStringContainsString('cleaning', $contents);
        $this->assertStringContainsString('maintenance', $contents);
    }
}

