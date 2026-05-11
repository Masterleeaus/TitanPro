<?php

use App\Models\Customer;
use App\Models\DriverLocation;
use App\Models\Item;
use App\Models\Job;
use App\Models\JobChecklistItem;
use App\Models\JobType;
use App\Models\JobTypeChecklistItem;
use App\Models\MessageTemplate;
use App\Models\Organization;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/**
 * Verifies that every admin resource's edit page returns 404 when the record
 * belongs to a different organization — confirming the org-scoping in
 * getEloquentQuery() is enforced at the HTTP level.
 */
beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

function scopedOwner(): array
{
    $myOrg    = Organization::factory()->create();
    $user     = User::factory()->create(['organization_id' => $myOrg->id]);
    $user->assignRole('owner');

    $otherOrg = Organization::factory()->create();

    return [$user, $myOrg, $otherOrg];
}

test('customers edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $record = Customer::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/admin/customers/{$record->id}/edit")->assertNotFound();
});

test('properties edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $record = Property::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/admin/properties/{$record->id}/edit")->assertNotFound();
});

test('job-types edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $record = JobType::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/admin/job-types/{$record->id}/edit")->assertNotFound();
});

test('items edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $record = Item::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/admin/items/{$record->id}/edit")->assertNotFound();
});

test('jobs view page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $customer = Customer::factory()->create(['organization_id' => $other->id]);
    $record   = Job::factory()->forCustomer($customer)->create();

    $this->actingAs($user)->get("/admin/jobs/{$record->id}")->assertNotFound();
});

test('driver-locations edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $otherUser = User::factory()->create(['organization_id' => $other->id]);
    $record    = DriverLocation::create([
        'organization_id' => $other->id,
        'user_id'         => $otherUser->id,
        'latitude'        => 40.7128,
        'longitude'       => -74.0060,
        'recorded_at'     => now(),
    ]);

    $this->actingAs($user)->get("/admin/driver-locations/{$record->id}/edit")->assertNotFound();
});

test('organization-settings edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $record = \App\Models\OrganizationSetting::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/admin/organization-settings/{$record->id}/edit")->assertNotFound();
});

// ── Issue 197: null-safe guard audit ─────────────────────────────────────────

test('job-type-checklist-items edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $jobType = JobType::factory()->create(['organization_id' => $other->id]);
    $record  = JobTypeChecklistItem::factory()->create(['job_type_id' => $jobType->id]);

    $this->actingAs($user)->get("/admin/job-type-checklist-items/{$record->id}/edit")->assertNotFound();
});

test('job-checklist-items edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $customer = Customer::factory()->create(['organization_id' => $other->id]);
    $job      = Job::factory()->forCustomer($customer)->create();
    $record   = JobChecklistItem::create([
        'organization_id' => $other->id,
        'job_id'          => $job->id,
        'label'           => 'Other org task',
        'sort_order'      => 1,
        'is_required'     => false,
        'requires_photo'  => false,
    ]);

    $this->actingAs($user)->get("/admin/job-checklist-items/{$record->id}/edit")->assertNotFound();
});

test('message-templates edit page 404s for other-org record', function () {
    [$user, , $other] = scopedOwner();
    $record = MessageTemplate::create([
        'organization_id' => $other->id,
        'event'           => 'job_scheduled',
        'channel'         => 'email',
        'subject'         => 'Test',
        'body'            => 'Test body',
    ]);

    $this->actingAs($user)->get("/admin/message-templates/{$record->id}/edit")->assertNotFound();
});
