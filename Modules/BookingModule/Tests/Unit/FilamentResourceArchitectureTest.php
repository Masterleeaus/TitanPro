<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class FilamentResourceArchitectureTest extends TestCase
{
    public function test_booking_filament_resource_does_not_contain_booking_mutation_logic(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Filament/Resources/BookingResource.php'));

        $this->assertNotFalse($contents);
        $this->assertStringNotContainsString('BookingFSMService', $contents);
        $this->assertStringNotContainsString('TransitionCleaningBookingAction', $contents);
        $this->assertStringNotContainsString('BookingApprovalRuntime', $contents);
    }
}

