<?php

use App\Models\Customer;
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
