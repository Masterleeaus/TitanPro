<?php

use App\Models\DriverLocation;
use App\Models\Organization;
use App\Models\User;
use App\Support\CleaningAdminMetrics;
use Database\Seeders\RolesAndPermissionsSeeder;

test('cleaner map roster includes technicians without a location ping', function () {
    (new RolesAndPermissionsSeeder)->run();

    $organization = Organization::factory()->create();
    $owner = User::factory()->create(['organization_id' => $organization->id]);
    $owner->assignRole('owner');

    $technician = User::factory()->create(['organization_id' => $organization->id]);
    $technician->assignRole('technician');

    $this->actingAs($owner, 'web');
    $roster = CleaningAdminMetrics::cleanerMapRoster();

    expect($roster)->toHaveCount(1)
        ->and($roster->first()['id'])->toBe($technician->id)
        ->and($roster->first()['location'])->toBeNull();
});

test('cleaner map roster reads latest technician location from driver_locations', function () {
    (new RolesAndPermissionsSeeder)->run();

    $organization = Organization::factory()->create();
    $owner = User::factory()->create(['organization_id' => $organization->id]);
    $owner->assignRole('owner');

    $technician = User::factory()->create(['organization_id' => $organization->id]);
    $technician->assignRole('technician');

    DriverLocation::create([
        'organization_id' => $organization->id,
        'user_id' => $technician->id,
        'latitude' => -33.8600,
        'longitude' => 151.2000,
        'recorded_at' => now()->subMinutes(10),
    ]);

    DriverLocation::create([
        'organization_id' => $organization->id,
        'user_id' => $technician->id,
        'latitude' => -33.8688,
        'longitude' => 151.2093,
        'recorded_at' => now(),
    ]);

    $this->actingAs($owner, 'web');
    $roster = CleaningAdminMetrics::cleanerMapRoster();

    expect($roster)->toHaveCount(1)
        ->and((float) $roster->first()['location']->latitude)->toBe(-33.8688)
        ->and((float) $roster->first()['location']->longitude)->toBe(151.2093);
});

test('cleaner live map widget includes a refresh button and unknown-location state copy', function () {
    $blade = file_get_contents(resource_path('views/filament/widgets/cleaner-live-map.blade.php'));

    expect($blade)->toContain('Refresh Locations')
        ->and($blade)->toContain('Location unknown')
        ->and($blade)->toContain('const first = points[0] ?? { lat: -33.8688, lng: 151.2093 }');
});
