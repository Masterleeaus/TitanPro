<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class ApprovalRuntimeConfigTest extends TestCase
{
    public function test_automation_config_declares_approval_threshold_and_timeout(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Config/automation.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString("'high_value_threshold'", $contents);
        $this->assertStringContainsString("'timeout_hours'", $contents);
    }
}
