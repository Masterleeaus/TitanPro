<?php

namespace Modules\BookingModule\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteCoverageTest extends TestCase
{
    public function test_critical_bookingmodule_routes_are_registered(): void
    {
        $routeNames = [
            'appointment.dispatch',
            'appointment.dispatch.move',
            'appointment.dispatch.schedule.edit',
            'appointment.dispatch.schedule.update',
            'cleaning-bookings.store',
            'cleaning-bookings.update-status',
            'cleaning-bookings.assign-cleaner',
            'booking.pages.request.store',
            'booking.pages.show',
            'booking.status.show',
        ];

        foreach ($routeNames as $routeName) {
            $this->assertTrue(Route::has($routeName), "Expected route [{$routeName}] to be registered.");
        }
    }

    public function test_dispatch_and_cleaning_routes_have_expected_http_methods(): void
    {
        $methodChecks = [
            'appointment.dispatch.move' => 'POST',
            'appointment.dispatch.schedule.update' => 'POST',
            'cleaning-bookings.store' => 'POST',
            'cleaning-bookings.update-status' => 'PATCH',
            'cleaning-bookings.assign-cleaner' => 'PATCH',
            'booking.pages.request.store' => 'POST',
        ];

        foreach ($methodChecks as $routeName => $method) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Route [{$routeName}] not found.");
            $this->assertContains($method, $route->methods(), "Route [{$routeName}] does not include [{$method}] method.");
        }
    }
}
