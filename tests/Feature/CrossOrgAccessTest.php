<?php

/**
 * Cross-org access tests for every org-scoped resource and policy.
 *
 * Verifies three layers of defence for every relevant model:
 *   1. Policy methods return false when the authenticated user belongs to a
 *      different organisation than the target model.
 *   2. Eloquent TenantScope ensures listing returns only the current org's rows.
 *   3. HTTP endpoints (owner panel or Filament admin) return 404 / 403 when
 *      a record belonging to another org is requested.
 *
 * Follows the pattern established in tests/Feature/Admin/OrgScopingTest.php
 * and tests/Feature/TenantIsolationTest.php.
 *
 * Acceptance criteria (issue-197 / follow-up to #129, #130, #131):
 *   Invoice, Payment, Estimate, EstimatePackage, Attachment, OrganizationSetting,
 *   JobMessage, DriverLocation, Job, Customer, Property, Item, JobType,
 *   JobChecklistItem, MessageTemplate.
 */

use App\Models\Attachment;
use App\Models\Customer;
use App\Models\DriverLocation;
use App\Models\Estimate;
use App\Models\EstimatePackage;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Job;
use App\Models\JobChecklistItem;
use App\Models\JobMessage;
use App\Models\JobType;
use App\Models\MessageTemplate;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use App\Policies\AttachmentPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\DriverLocationPolicy;
use App\Policies\EstimatePackagePolicy;
use App\Policies\EstimatePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\ItemPolicy;
use App\Policies\JobChecklistItemPolicy;
use App\Policies\JobMessagePolicy;
use App\Policies\JobPolicy;
use App\Policies\JobTypePolicy;
use App\Policies\MessageTemplatePolicy;
use App\Policies\OrganizationSettingPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PropertyPolicy;
use Database\Seeders\RolesAndPermissionsSeeder;

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Create two users in two separate orgs and seed roles/permissions once.
 * Returns [$userA, $orgA, $orgB].
 */
function crossOrgSetup(string $role = 'owner'): array
{
    (new RolesAndPermissionsSeeder)->run();

    $orgA  = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole($role);

    $orgB = Organization::factory()->create();

    return [$userA, $orgA, $orgB];
}

/**
 * Create a super_admin user scoped to an org (for Filament /titanpro panel tests).
 */
function scopedAdmin(): array
{
    (new RolesAndPermissionsSeeder)->run();

    $myOrg = Organization::factory()->create();
    $user  = User::factory()->create(['organization_id' => $myOrg->id]);
    $user->assignRole('super_admin');

    $otherOrg = Organization::factory()->create();

    return [$user, $myOrg, $otherOrg];
}

// ═════════════════════════════════════════════════════════════════════════════
// PART 1 — POLICY: view() returns false for cross-org models
// ═════════════════════════════════════════════════════════════════════════════

test('InvoicePolicy::view returns false for foreign-org invoice', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoice   = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new InvoicePolicy)->view($user, $invoice))->toBeFalse();
});

test('InvoicePolicy::update returns false for foreign-org invoice', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoice   = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new InvoicePolicy)->update($user, $invoice))->toBeFalse();
});

test('InvoicePolicy::delete returns false for foreign-org invoice', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoice   = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new InvoicePolicy)->delete($user, $invoice))->toBeFalse();
});

test('PaymentPolicy::view returns false for foreign-org payment', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoiceB  = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $payment   = Payment::factory()->forInvoice($invoiceB)->create();

    expect((new PaymentPolicy)->view($user, $payment))->toBeFalse();
});

test('PaymentPolicy::update returns false for foreign-org payment', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoiceB  = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $payment   = Payment::factory()->forInvoice($invoiceB)->create();

    expect((new PaymentPolicy)->update($user, $payment))->toBeFalse();
});

test('PaymentPolicy::delete returns false for foreign-org payment', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoiceB  = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $payment   = Payment::factory()->forInvoice($invoiceB)->create();

    expect((new PaymentPolicy)->delete($user, $payment))->toBeFalse();
});

test('EstimatePolicy::view returns false for foreign-org estimate', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new EstimatePolicy)->view($user, $estimate))->toBeFalse();
});

test('EstimatePolicy::update returns false for foreign-org estimate', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new EstimatePolicy)->update($user, $estimate))->toBeFalse();
});

test('EstimatePolicy::delete returns false for foreign-org estimate', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new EstimatePolicy)->delete($user, $estimate))->toBeFalse();
});

test('EstimatePackagePolicy::view returns false for foreign-org package', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $package   = EstimatePackage::factory()->forEstimate($estimateB)->create();
    $package->setRelation('estimate', $estimateB);

    expect((new EstimatePackagePolicy)->view($user, $package))->toBeFalse();
});

test('EstimatePackagePolicy::update returns false for foreign-org package', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $package   = EstimatePackage::factory()->forEstimate($estimateB)->create();
    $package->setRelation('estimate', $estimateB);

    expect((new EstimatePackagePolicy)->update($user, $package))->toBeFalse();
});

test('EstimatePackagePolicy::delete returns false for foreign-org package', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $package   = EstimatePackage::factory()->forEstimate($estimateB)->create();
    $package->setRelation('estimate', $estimateB);

    expect((new EstimatePackagePolicy)->delete($user, $package))->toBeFalse();
});

test('AttachmentPolicy::view returns false for foreign-org attachment', function () {
    [$user, , $orgB] = crossOrgSetup();
    $attachment = Attachment::factory()->create(['organization_id' => $orgB->id]);

    expect((new AttachmentPolicy)->view($user, $attachment))->toBeFalse();
});

test('AttachmentPolicy::update returns false for foreign-org attachment', function () {
    [$user, , $orgB] = crossOrgSetup();
    $attachment = Attachment::factory()->create(['organization_id' => $orgB->id]);

    expect((new AttachmentPolicy)->update($user, $attachment))->toBeFalse();
});

test('AttachmentPolicy::delete returns false for foreign-org attachment', function () {
    [$user, , $orgB] = crossOrgSetup();
    $attachment = Attachment::factory()->create(['organization_id' => $orgB->id]);

    expect((new AttachmentPolicy)->delete($user, $attachment))->toBeFalse();
});

test('OrganizationSettingPolicy::view returns false for foreign-org setting', function () {
    [$user, , $orgB] = crossOrgSetup();
    $setting = OrganizationSetting::factory()->create(['organization_id' => $orgB->id]);

    expect((new OrganizationSettingPolicy)->view($user, $setting))->toBeFalse();
});

test('OrganizationSettingPolicy::update returns false for foreign-org setting', function () {
    [$user, , $orgB] = crossOrgSetup();
    $setting = OrganizationSetting::factory()->create(['organization_id' => $orgB->id]);

    expect((new OrganizationSettingPolicy)->update($user, $setting))->toBeFalse();
});

test('OrganizationSettingPolicy::delete returns false for foreign-org setting', function () {
    [$user, , $orgB] = crossOrgSetup();
    $setting = OrganizationSetting::factory()->create(['organization_id' => $orgB->id]);

    expect((new OrganizationSettingPolicy)->delete($user, $setting))->toBeFalse();
});

test('JobMessagePolicy::view returns false for foreign-org job message', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobB      = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $message   = JobMessage::factory()->forJob($jobB)->create();
    $message->setRelation('job', $jobB);

    expect((new JobMessagePolicy)->view($user, $message))->toBeFalse();
});

test('JobMessagePolicy::update returns false for foreign-org job message', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobB      = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $message   = JobMessage::factory()->forJob($jobB)->create();
    $message->setRelation('job', $jobB);

    expect((new JobMessagePolicy)->update($user, $message))->toBeFalse();
});

test('JobMessagePolicy::delete returns false for foreign-org job message', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobB      = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $message   = JobMessage::factory()->forJob($jobB)->create();
    $message->setRelation('job', $jobB);

    expect((new JobMessagePolicy)->delete($user, $message))->toBeFalse();
});

test('JobChecklistItemPolicy::view returns false for foreign-org checklist item', function () {
    [$user, , $orgB] = crossOrgSetup();
    $item = JobChecklistItem::factory()->create(['organization_id' => $orgB->id]);

    expect((new JobChecklistItemPolicy)->view($user, $item))->toBeFalse();
});

test('JobChecklistItemPolicy::update returns false for foreign-org checklist item', function () {
    [$user, , $orgB] = crossOrgSetup();
    $item = JobChecklistItem::factory()->create(['organization_id' => $orgB->id]);

    expect((new JobChecklistItemPolicy)->update($user, $item))->toBeFalse();
});

test('JobChecklistItemPolicy::delete returns false for foreign-org checklist item', function () {
    [$user, , $orgB] = crossOrgSetup();
    $item = JobChecklistItem::factory()->create(['organization_id' => $orgB->id]);

    expect((new JobChecklistItemPolicy)->delete($user, $item))->toBeFalse();
});

test('MessageTemplatePolicy::view returns false for foreign-org template', function () {
    [$user, , $orgB] = crossOrgSetup();
    $template = MessageTemplate::factory()->create(['organization_id' => $orgB->id]);

    expect((new MessageTemplatePolicy)->view($user, $template))->toBeFalse();
});

test('MessageTemplatePolicy::update returns false for foreign-org template', function () {
    [$user, , $orgB] = crossOrgSetup();
    $template = MessageTemplate::factory()->create(['organization_id' => $orgB->id]);

    expect((new MessageTemplatePolicy)->update($user, $template))->toBeFalse();
});

test('MessageTemplatePolicy::delete returns false for foreign-org template', function () {
    [$user, , $orgB] = crossOrgSetup();
    $template = MessageTemplate::factory()->create(['organization_id' => $orgB->id]);

    expect((new MessageTemplatePolicy)->delete($user, $template))->toBeFalse();
});

test('DriverLocationPolicy::view returns false for foreign-org driver location', function () {
    [$user, , $orgB] = crossOrgSetup();
    $otherUser = User::factory()->create(['organization_id' => $orgB->id]);
    $location  = DriverLocation::create([
        'organization_id' => $orgB->id,
        'user_id'         => $otherUser->id,
        'latitude'        => 40.7128,
        'longitude'       => -74.0060,
        'recorded_at'     => now(),
    ]);

    expect((new DriverLocationPolicy)->view($user, $location))->toBeFalse();
});

test('DriverLocationPolicy::update returns false for foreign-org driver location', function () {
    [$user, , $orgB] = crossOrgSetup();
    $otherUser = User::factory()->create(['organization_id' => $orgB->id]);
    $location  = DriverLocation::create([
        'organization_id' => $orgB->id,
        'user_id'         => $otherUser->id,
        'latitude'        => 40.7128,
        'longitude'       => -74.0060,
        'recorded_at'     => now(),
    ]);

    expect((new DriverLocationPolicy)->update($user, $location))->toBeFalse();
});

test('JobPolicy::view returns false for foreign-org job', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $job       = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new JobPolicy)->view($user, $job))->toBeFalse();
});

test('JobPolicy::update returns false for foreign-org job', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $job       = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new JobPolicy)->update($user, $job))->toBeFalse();
});

test('JobPolicy::delete returns false for foreign-org job', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $job       = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    expect((new JobPolicy)->delete($user, $job))->toBeFalse();
});

test('CustomerPolicy::view returns false for foreign-org customer', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customer = Customer::factory()->create(['organization_id' => $orgB->id]);

    expect((new CustomerPolicy)->view($user, $customer))->toBeFalse();
});

test('CustomerPolicy::update returns false for foreign-org customer', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customer = Customer::factory()->create(['organization_id' => $orgB->id]);

    expect((new CustomerPolicy)->update($user, $customer))->toBeFalse();
});

test('CustomerPolicy::delete returns false for foreign-org customer', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customer = Customer::factory()->create(['organization_id' => $orgB->id]);

    expect((new CustomerPolicy)->delete($user, $customer))->toBeFalse();
});

test('PropertyPolicy::view returns false for foreign-org property', function () {
    [$user, , $orgB] = crossOrgSetup();
    $property = Property::factory()->create(['organization_id' => $orgB->id]);

    expect((new PropertyPolicy)->view($user, $property))->toBeFalse();
});

test('PropertyPolicy::update returns false for foreign-org property', function () {
    [$user, , $orgB] = crossOrgSetup();
    $property = Property::factory()->create(['organization_id' => $orgB->id]);

    expect((new PropertyPolicy)->update($user, $property))->toBeFalse();
});

test('PropertyPolicy::delete returns false for foreign-org property', function () {
    [$user, , $orgB] = crossOrgSetup();
    $property = Property::factory()->create(['organization_id' => $orgB->id]);

    expect((new PropertyPolicy)->delete($user, $property))->toBeFalse();
});

test('ItemPolicy::view returns false for foreign-org item', function () {
    [$user, , $orgB] = crossOrgSetup();
    $item = Item::factory()->create(['organization_id' => $orgB->id]);

    expect((new ItemPolicy)->view($user, $item))->toBeFalse();
});

test('ItemPolicy::update returns false for foreign-org item', function () {
    [$user, , $orgB] = crossOrgSetup();
    $item = Item::factory()->create(['organization_id' => $orgB->id]);

    expect((new ItemPolicy)->update($user, $item))->toBeFalse();
});

test('ItemPolicy::delete returns false for foreign-org item', function () {
    [$user, , $orgB] = crossOrgSetup();
    $item = Item::factory()->create(['organization_id' => $orgB->id]);

    expect((new ItemPolicy)->delete($user, $item))->toBeFalse();
});

test('JobTypePolicy::view returns false for foreign-org job type', function () {
    [$user, , $orgB] = crossOrgSetup();
    $jobType = JobType::factory()->create(['organization_id' => $orgB->id]);

    expect((new JobTypePolicy)->view($user, $jobType))->toBeFalse();
});

test('JobTypePolicy::update returns false for foreign-org job type', function () {
    [$user, , $orgB] = crossOrgSetup();
    $jobType = JobType::factory()->create(['organization_id' => $orgB->id]);

    expect((new JobTypePolicy)->update($user, $jobType))->toBeFalse();
});

test('JobTypePolicy::delete returns false for foreign-org job type', function () {
    [$user, , $orgB] = crossOrgSetup();
    $jobType = JobType::factory()->create(['organization_id' => $orgB->id]);

    expect((new JobTypePolicy)->delete($user, $jobType))->toBeFalse();
});

// ═════════════════════════════════════════════════════════════════════════════
// PART 2 — ELOQUENT SCOPE: listing returns only the current org's rows
// ═════════════════════════════════════════════════════════════════════════════

test('authenticated user only sees their own payments', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $orgB = Organization::factory()->create();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoiceA  = Invoice::factory()->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    $invoiceB  = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    Payment::factory()->forInvoice($invoiceA)->create();
    Payment::factory()->forInvoice($invoiceA)->create();
    Payment::factory()->forInvoice($invoiceB)->create();

    $this->actingAs($userA);

    $payments = Payment::all();

    expect($payments)->toHaveCount(2)
        ->and($payments->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user only sees their own estimates', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $orgB = Organization::factory()->create();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    Estimate::factory()->count(2)->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    Estimate::factory()->count(3)->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $estimates = Estimate::all();

    expect($estimates)->toHaveCount(2)
        ->and($estimates->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user only sees their own message templates', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $orgB = Organization::factory()->create();

    MessageTemplate::factory()->forOrg($orgA->id, 'job_scheduled', 'email')->create();
    MessageTemplate::factory()->forOrg($orgA->id, 'en_route', 'sms')->create();
    MessageTemplate::factory()->forOrg($orgB->id, 'job_scheduled', 'email')->create();
    MessageTemplate::factory()->forOrg($orgB->id, 'job_completed', 'sms')->create();

    $this->actingAs($userA);

    $templates = MessageTemplate::all();

    expect($templates)->toHaveCount(2)
        ->and($templates->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user cannot find a payment belonging to another org', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $orgB      = Organization::factory()->create();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoiceB  = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $payment   = Payment::factory()->forInvoice($invoiceB)->create();

    $this->actingAs($userA);

    expect(Payment::find($payment->id))->toBeNull();
});

test('authenticated user cannot find an estimate belonging to another org', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $orgB      = Organization::factory()->create();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    expect(Estimate::find($estimate->id))->toBeNull();
});

// ═════════════════════════════════════════════════════════════════════════════
// PART 3 — OWNER PANEL HTTP: cross-org access returns 404 / 403
// ═════════════════════════════════════════════════════════════════════════════

test('org A user cannot view org B invoice via owner HTTP', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoice   = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $response = $this->actingAs($user)->get("/owner/invoices/{$invoice->id}");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A user cannot delete org B invoice via owner HTTP', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $invoice   = Invoice::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $response = $this->actingAs($user)->delete("/owner/invoices/{$invoice->id}");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A user cannot view org B estimate via owner HTTP', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $response = $this->actingAs($user)->get("/owner/estimates/{$estimate->id}");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A user cannot edit org B estimate via owner HTTP', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $response = $this->actingAs($user)->get("/owner/estimates/{$estimate->id}/edit");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A user cannot delete org B estimate via owner HTTP', function () {
    [$user, , $orgB] = crossOrgSetup();
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimate  = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $response = $this->actingAs($user)->delete("/owner/estimates/{$estimate->id}");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A invoice index never includes org B invoices', function () {
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole('owner');

    $orgB = Organization::factory()->create();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    Invoice::factory()->count(2)->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    Invoice::factory()->count(3)->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA)
        ->get('/owner/invoices')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('invoices.data', 2));
});

// Note: GET /owner/estimates redirects to /titanquotes (line 60 of web.php), so
// estimate listing is covered by the Eloquent scope test in Part 2 above.

// ═════════════════════════════════════════════════════════════════════════════
// PART 4 — FILAMENT ADMIN PANEL: edit page 404s for other-org record
// Uses the /titanpro panel path; super_admin role required.
// ═════════════════════════════════════════════════════════════════════════════

test('invoices admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $customerB = Customer::factory()->create(['organization_id' => $other->id]);
    $record    = Invoice::factory()->create(['organization_id' => $other->id, 'customer_id' => $customerB->id]);

    $this->actingAs($user)->get("/titanpro/invoices/{$record->id}/edit")->assertNotFound();
});

test('payments admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $customerB = Customer::factory()->create(['organization_id' => $other->id]);
    $invoiceB  = Invoice::factory()->create(['organization_id' => $other->id, 'customer_id' => $customerB->id]);
    $record    = Payment::factory()->forInvoice($invoiceB)->create();

    $this->actingAs($user)->get("/titanpro/payments/{$record->id}/edit")->assertNotFound();
});

test('estimates admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $customerB = Customer::factory()->create(['organization_id' => $other->id]);
    $record    = Estimate::factory()->create(['organization_id' => $other->id, 'customer_id' => $customerB->id]);

    $this->actingAs($user)->get("/titanpro/estimates/{$record->id}/edit")->assertNotFound();
});

test('estimate-packages admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $customerB = Customer::factory()->create(['organization_id' => $other->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $other->id, 'customer_id' => $customerB->id]);
    $record    = EstimatePackage::factory()->forEstimate($estimateB)->create();

    $this->actingAs($user)->get("/titanpro/estimate-packages/{$record->id}/edit")->assertNotFound();
});

test('attachments admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $record = Attachment::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/titanpro/attachments/{$record->id}/edit")->assertNotFound();
});

test('job-messages admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $customerB = Customer::factory()->create(['organization_id' => $other->id]);
    $jobB      = Job::factory()->create(['organization_id' => $other->id, 'customer_id' => $customerB->id]);
    $record    = JobMessage::factory()->forJob($jobB)->create();

    $this->actingAs($user)->get("/titanpro/job-messages/{$record->id}/edit")->assertNotFound();
});

test('message-templates admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $record = MessageTemplate::factory()->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/titanpro/message-templates/{$record->id}/edit")->assertNotFound();
});

test('job-checklist-items admin edit page 404s for other-org record', function () {
    [$user, , $other] = scopedAdmin();
    $customerB = Customer::factory()->create(['organization_id' => $other->id]);
    $jobB      = Job::factory()->create(['organization_id' => $other->id, 'customer_id' => $customerB->id]);
    $record    = JobChecklistItem::factory()->forJob($jobB)->create(['organization_id' => $other->id]);

    $this->actingAs($user)->get("/titanpro/job-checklist-items/{$record->id}/edit")->assertNotFound();
});
