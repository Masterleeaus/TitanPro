<?php

use App\Filament\GroundZero\Resources\CustomerResource;
use App\Filament\GroundZero\Resources\EstimateResource;
use App\Filament\GroundZero\Resources\InvoiceResource;
use App\Filament\GroundZero\Resources\JobResource;
use App\Filament\GroundZero\Resources\PropertyResource;
use App\Filament\GroundZero\Resources\TeamResource;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Organization;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function groundZeroOwner(): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    return $user;
}

function groundZeroDispatcher(): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('dispatcher');

    return $user;
}

function groundZeroBookkeeper(): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('bookkeeper');

    return $user;
}

// ── Resource route accessibility ─────────────────────────────────────────────

test('owner can access the GroundZero jobs list', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/jobs')->assertOk();
});

test('dispatcher can access the GroundZero jobs list', function () {
    $user = groundZeroDispatcher();
    $this->actingAs($user)->get('/groundzero/jobs')->assertOk();
});

test('owner can access the GroundZero customers list', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/customers')->assertOk();
});

test('owner can access the GroundZero properties list', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/properties')->assertOk();
});

test('bookkeeper can access the GroundZero invoices list', function () {
    $user = groundZeroBookkeeper();
    $this->actingAs($user)->get('/groundzero/invoices')->assertOk();
});

test('owner can access the GroundZero estimates list', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/estimates')->assertOk();
});

test('owner can access the GroundZero team list', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/team')->assertOk();
});

// ── Custom pages ──────────────────────────────────────────────────────────────

test('owner can access the GroundZero dispatch board', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/dispatch-board')->assertOk();
});

test('dispatcher can access the GroundZero dispatch board', function () {
    $user = groundZeroDispatcher();
    $this->actingAs($user)->get('/groundzero/dispatch-board')->assertOk();
});

test('owner can access the GroundZero calendar page', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/calendar-page')->assertOk();
});

test('owner can access the GroundZero reports page', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/reports-page')->assertOk();
});

test('owner can access the GroundZero settings page', function () {
    $user = groundZeroOwner();
    $this->actingAs($user)->get('/groundzero/settings-page')->assertOk();
});

// ── Tenant scoping — jobs ────────────────────────────────────────────────────

test('GroundZero getEloquentQuery for JobResource returns only org-scoped results', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA     = Organization::factory()->create();
    $orgB     = Organization::factory()->create();
    $userA    = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobA = Job::factory()->forCustomer($customerA)->create();
    $jobB = Job::factory()->forCustomer($customerB)->create();

    $this->actingAs($userA);

    $query = JobResource::getEloquentQuery();

    expect($query->pluck('id'))->toContain($jobA->id)
        ->and($query->pluck('id'))->not->toContain($jobB->id);
});

test('GroundZero getEloquentQuery for CustomerResource returns only org-scoped results', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA  = Organization::factory()->create();
    $orgB  = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA);

    $ids = CustomerResource::getEloquentQuery()->pluck('id');

    expect($ids)->toContain($customerA->id)
        ->and($ids)->not->toContain($customerB->id);
});

test('GroundZero getEloquentQuery for PropertyResource returns only org-scoped results', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA  = Organization::factory()->create();
    $orgB  = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $propA = Property::factory()->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    $propB = Property::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $ids = PropertyResource::getEloquentQuery()->pluck('id');

    expect($ids)->toContain($propA->id)
        ->and($ids)->not->toContain($propB->id);
});

test('GroundZero getEloquentQuery for InvoiceResource returns only org-scoped results', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA  = Organization::factory()->create();
    $orgB  = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $invoiceA = Invoice::factory()->create(['organization_id' => $orgA->id]);
    $invoiceB = Invoice::factory()->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA);

    $ids = InvoiceResource::getEloquentQuery()->pluck('id');

    expect($ids)->toContain($invoiceA->id)
        ->and($ids)->not->toContain($invoiceB->id);
});

test('GroundZero getEloquentQuery for EstimateResource returns only org-scoped results', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA  = Organization::factory()->create();
    $orgB  = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $estimateA = Estimate::factory()->create(['organization_id' => $orgA->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA);

    $ids = EstimateResource::getEloquentQuery()->pluck('id');

    expect($ids)->toContain($estimateA->id)
        ->and($ids)->not->toContain($estimateB->id);
});

test('GroundZero getEloquentQuery returns empty for unauthenticated user', function () {
    $query = JobResource::getEloquentQuery();
    expect($query->count())->toBe(0);
});
