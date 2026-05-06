<?php

return [
    'group' => 'Booking & Dispatch',
    'mode' => env('BOOKINGMODULE_UI_MODE', 'hybrid'),
    'items' => [
        ['label' => 'Bookings', 'route' => 'bookings.index', 'permission' => 'view_booking'],
        ['label' => 'Booking Dashboard', 'route' => 'appointment.dashboard', 'permission' => 'appointment dashboard manage'],
        ['label' => 'Appointments', 'route' => 'appointments.index', 'permission' => 'appointment manage'],
        ['label' => 'Schedules', 'route' => 'appointment.schedules.index', 'permission' => 'schedule manage'],
        ['label' => 'Dispatch Board', 'route' => 'admin.dispatch.board', 'permission' => 'appointment dispatch'],
        ['label' => 'Booking Pages', 'route' => 'admin.booking.pages.index', 'permission' => 'view_booking_pages'],
    ],
];
