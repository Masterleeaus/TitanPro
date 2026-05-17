<?php

namespace Modules\BookingModule\Tests\Feature;

use Tests\TestCase;

class RouteCoverageTest extends TestCase
{
    public function test_critical_bookingmodule_routes_are_declared(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Routes/web.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString("->name('appointment.dispatch')", $contents);
        $this->assertStringContainsString("->name('appointment.dispatch.move')", $contents);
        $this->assertStringContainsString("->name('appointment.dispatch.schedule.edit')", $contents);
        $this->assertStringContainsString("->name('appointment.dispatch.schedule.update')", $contents);
        $this->assertStringContainsString("->as('cleaning-bookings.')", $contents);
        $this->assertStringContainsString("->name('store')", $contents);
        $this->assertStringContainsString("->name('update-status')", $contents);
        $this->assertStringContainsString("->name('assign-cleaner')", $contents);
        $this->assertStringContainsString("->name('booking.pages.request.store')", $contents);
        $this->assertStringContainsString("->name('booking.pages.show')", $contents);
        $this->assertStringContainsString("->name('booking.status.show')", $contents);
    }

    public function test_dispatch_and_cleaning_routes_keep_expected_http_verbs(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Routes/web.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString("Route::post('dispatch/move'", $contents);
        $this->assertStringContainsString("Route::post('dispatch/schedule/{id}'", $contents);
        $this->assertStringContainsString("Route::post('/', [\\Modules\\BookingModule\\Http\\Controllers\\Cleaning\\CleaningBookingController::class, 'store'])", $contents);
        $this->assertStringContainsString("Route::patch('{booking}/status'", $contents);
        $this->assertStringContainsString("Route::patch('{booking}/assign'", $contents);
        $this->assertStringContainsString("Route::middleware('web')->post('/book/{slug}/request'", $contents);
    }
}
