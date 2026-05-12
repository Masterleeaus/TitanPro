<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class VerticalConfigUsageTest extends TestCase
{
    public function test_vertical_pack_service_reads_vertical_values_from_module_config(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Services/VerticalPackService.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString("config('bookingmodule.verticals.supported'", $contents);
        $this->assertStringContainsString("config('bookingmodule.verticals.default'", $contents);
    }
}
