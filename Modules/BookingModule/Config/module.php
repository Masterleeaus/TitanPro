<?php

return [
    'name' => 'BookingModule',
    'alias' => 'bookingmodule',
    'display_name' => 'Booking & Dispatch',
    'description' => 'Booking, scheduling, dispatch, reminders and booking lifecycle automation.',
    'version' => trim((string) @file_get_contents(__DIR__ . '/../version.txt')) ?: '1.0.0',
    'engine' => true,
    'operator_surface' => [
        'legacy' => true,
        'filament' => env('BOOKINGMODULE_FILAMENT_ENABLED', false),
        'ui_mode' => env('BOOKINGMODULE_UI_MODE', 'hybrid'),
    ],
    'blueprint' => [
        'standard' => 'titan-ai-native-module',
        'aligned_at' => '2026-04-28',
        'owns_domain_logic' => true,
        'filament_is_operator_surface_only' => true,
    ],
];
