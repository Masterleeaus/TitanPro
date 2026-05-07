<?php

use App\Models\Customer;
use App\Models\DriverLocation;
use App\Models\Job;
use App\Models\Organization;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function dispatchSetup(): array
{
    (new RolesAndPermissionsSeeder)->run();

    $org   = Organization::factory()->create();
    $owner = User::factory()->create(['organization_id' => $org->id]);
    $owner->assignRole('owner');

    $tech = User::factory()->create(['organization_id' => $org->id]);
    $tech->assignRole('technician');

    $customer = Customer::factory()->create(['organization_id' => $org->id]);
    $property = Property::factory()->create([
        'organization_id' => $org->id,
        'customer_id'     => $customer->id,
    ]);

    return [$owner, $tech, $org, $customer, $property];
}

// ── Dispatch index ────────────────────────────────────────────────────────────

test('dispatch map requires authentication', function () {
    $this->get('/owner/dispatch')->assertRedirect('/login');
});

test('owner can view dispatch map page', function () {
    [$owner] = dispatchSetup();

    $this->actingAs($owner)
        ->get('/owner/dispatch')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Owner/Dispatch/Map'));
});

// ── technicianLocations JSON endpoint ─────────────────────────────────────────

test('technician locations endpoint requires authentication', function () {
    $this->getJson('/owner/dispatch/technicians')->assertUnauthorized();
});

test('technician locations returns empty data when no technicians exist', function () {
    (new RolesAndPermissionsSeeder)->run();
    $org   = Organization::factory()->create();
    $owner = User::factory()->create(['organization_id' => $org->id]);
    $owner->assignRole('owner');

    $this->actingAs($owner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk()
        ->assertJson(['data' => []]);
});

test('technician locations returns technician entries scoped to the owner organization', function () {
    [$owner, $tech] = dispatchSetup();

    // Technician from a different org — must not appear
    $otherOrg  = Organization::factory()->create();
    $otherTech = User::factory()->create(['organization_id' => $otherOrg->id]);
    $otherTech->assignRole('technician');

    $response = $this->actingAs($owner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk();

    $ids = collect($response->json('data'))->pluck('id');
    expect($ids)->toContain($tech->id)
        ->not->toContain($otherTech->id);
});

test('technician locations includes active job pre-keyed from one query', function () {
    [$owner, $tech, $org, $customer, $property] = dispatchSetup();

    $job = Job::factory()->forCustomer($customer)->create([
        'assigned_to' => $tech->id,
        'status'      => Job::STATUS_IN_PROGRESS,
        'scheduled_at' => now(),
        'property_id' => $property->id,
    ]);

    $response = $this->actingAs($owner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk();

    $techData = collect($response->json('data'))->firstWhere('id', $tech->id);

    expect($techData)->not->toBeNull()
        ->and($techData['current_job'])->not->toBeNull()
        ->and($techData['current_job']['id'])->toBe($job->id);
});

test('technician locations returns most recently scheduled active job when technician has multiple', function () {
    [$owner, $tech, $org, $customer, $property] = dispatchSetup();

    $older = Job::factory()->forCustomer($customer)->create([
        'assigned_to'  => $tech->id,
        'status'       => Job::STATUS_EN_ROUTE,
        'scheduled_at' => now()->subHour(),
        'property_id'  => $property->id,
    ]);

    $newer = Job::factory()->forCustomer($customer)->create([
        'assigned_to'  => $tech->id,
        'status'       => Job::STATUS_IN_PROGRESS,
        'scheduled_at' => now(),
        'property_id'  => $property->id,
    ]);

    $response = $this->actingAs($owner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk();

    $techData = collect($response->json('data'))->firstWhere('id', $tech->id);
    expect($techData['current_job']['id'])->toBe($newer->id);
});

test('technician locations includes up to 3 upcoming scheduled jobs', function () {
    [$owner, $tech, $org, $customer] = dispatchSetup();

    Job::factory()->forCustomer($customer)->count(4)->create([
        'assigned_to'  => $tech->id,
        'status'       => Job::STATUS_SCHEDULED,
        'scheduled_at' => now(),
    ]);

    $response = $this->actingAs($owner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk();

    $techData = collect($response->json('data'))->firstWhere('id', $tech->id);
    expect($techData['upcoming_jobs'])->toHaveCount(3);
});

test('technician locations returns null location when no driver location exists', function () {
    [$owner, $tech] = dispatchSetup();

    $response = $this->actingAs($owner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk();

    $techData = collect($response->json('data'))->firstWhere('id', $tech->id);
    expect($techData['location'])->toBeNull();
});

test('technician locations do not leak another organization location data', function () {
    [$owner, $tech, $org] = dispatchSetup();

    DriverLocation::create([
        'organization_id' => $org->id,
        'user_id' => $tech->id,
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'recorded_at' => now(),
    ]);

    $otherOrg = Organization::factory()->create();
    $otherOwner = User::factory()->create(['organization_id' => $otherOrg->id]);
    $otherOwner->assignRole('owner');
    $otherTech = User::factory()->create(['organization_id' => $otherOrg->id]);
    $otherTech->assignRole('technician');

    DriverLocation::create([
        'organization_id' => $otherOrg->id,
        'user_id' => $otherTech->id,
        'latitude' => 34.0522,
        'longitude' => -118.2437,
        'recorded_at' => now(),
    ]);

    $response = $this->actingAs($otherOwner)
        ->getJson('/owner/dispatch/technicians')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.id'))->toBe($otherTech->id)
        ->and($response->json('data.0.location.latitude'))->toBe(34.0522)
        ->and(collect($response->json('data'))->pluck('id'))->not->toContain($tech->id);
});
