<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/**
 * Issue 197 follow-up — repurpose TitanPro InvoiceResource / PaymentResource as
 * cross-tenant super-admin finance views while ZeroPay remains the canonical
 * org-scoped finance panel for owner/admin/bookkeeper roles.
 *
 * These tests confirm that:
 *  - legacy /admin finance paths remain absent
 *  - TitanPro invoice/payment routes remain super_admin-only
 *  - super_admin can review cross-tenant TitanPro finance routes
 *  - ZeroPay owner/admin/bookkeeper finance routes remain org-scoped
 */

function zeroPayFinanceUser(string $role): array
{
    (new RolesAndPermissionsSeeder)->run();

    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole($role);

    return [$user, $organization];
}

// ── /admin/invoices — legacy path ────────────────────────────────────────────

test('admin user cannot reach /admin/invoices (404 — route no longer registered)', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/invoices')
        ->assertNotFound();
});

test('admin user cannot reach /admin/payments (404 — route no longer registered)', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/payments')
        ->assertNotFound();
});

// ── /titanpro/invoices — admin user must be denied ────────────────────────────

test('admin user is forbidden from the titanpro invoices page', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/titanpro/invoices')
        ->assertForbidden();
});

test('bookkeeper user is forbidden from the titanpro invoices page', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('bookkeeper');

    $this->actingAs($user)
        ->get('/titanpro/invoices')
        ->assertForbidden();
});

test('owner user is forbidden from the titanpro invoices page', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/titanpro/invoices')
        ->assertForbidden();
});

// ── /titanpro/payments — admin user must be denied ────────────────────────────

test('admin user is forbidden from the titanpro payments page', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/titanpro/payments')
        ->assertForbidden();
});

test('bookkeeper user is forbidden from the titanpro payments page', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('bookkeeper');

    $this->actingAs($user)
        ->get('/titanpro/payments')
        ->assertForbidden();
});

test('owner user is forbidden from the titanpro payments page', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/titanpro/payments')
        ->assertForbidden();
});

test('super admin can review cross-tenant invoices in titanpro', function () {
    (new RolesAndPermissionsSeeder)->run();

    $homeOrg = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $homeOrg->id]);
    $user->assignRole('super_admin');

    $otherOrg = Organization::factory()->create();
    $otherCustomer = Customer::factory()->create(['organization_id' => $otherOrg->id]);
    $invoice = Invoice::factory()->forCustomer($otherCustomer)->create([
        'invoice_number' => 'INV-CROSS-TENANT-197',
    ]);

    $this->actingAs($user)
        ->get('/titanpro/invoices')
        ->assertOk()
        ->assertSee('INV-CROSS-TENANT-197');

    $this->actingAs($user)
        ->get("/titanpro/invoices/{$invoice->id}/edit")
        ->assertOk();
});

test('super admin can review cross-tenant payments in titanpro', function () {
    (new RolesAndPermissionsSeeder)->run();

    $homeOrg = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $homeOrg->id]);
    $user->assignRole('super_admin');

    $otherOrg = Organization::factory()->create();
    $otherCustomer = Customer::factory()->create(['organization_id' => $otherOrg->id]);
    $invoice = Invoice::factory()->forCustomer($otherCustomer)->create();
    $payment = Payment::factory()->forInvoice($invoice)->create([
        'amount' => 321.09,
    ]);

    $this->actingAs($user)
        ->get('/titanpro/payments')
        ->assertOk()
        ->assertSee('321.09');

    $this->actingAs($user)
        ->get("/titanpro/payments/{$payment->id}/edit")
        ->assertOk();
});

test('owner sees only their organization invoices in zeropay', function () {
    [$user, $organization] = zeroPayFinanceUser('owner');

    $customer = Customer::factory()->create(['organization_id' => $organization->id]);
    Invoice::factory()->forCustomer($customer)->create(['invoice_number' => 'INV-ZP-OWNER-OWN']);

    $otherOrg = Organization::factory()->create();
    $otherCustomer = Customer::factory()->create(['organization_id' => $otherOrg->id]);
    Invoice::factory()->forCustomer($otherCustomer)->create(['invoice_number' => 'INV-ZP-OWNER-OTHER']);

    $this->actingAs($user)
        ->get('/zeropay/invoices')
        ->assertOk()
        ->assertSee('INV-ZP-OWNER-OWN')
        ->assertDontSee('INV-ZP-OWNER-OTHER');
});

test('admin sees only their organization invoices in zeropay', function () {
    [$user, $organization] = zeroPayFinanceUser('admin');

    $customer = Customer::factory()->create(['organization_id' => $organization->id]);
    Invoice::factory()->forCustomer($customer)->create(['invoice_number' => 'INV-ZP-ADMIN-OWN']);

    $otherOrg = Organization::factory()->create();
    $otherCustomer = Customer::factory()->create(['organization_id' => $otherOrg->id]);
    Invoice::factory()->forCustomer($otherCustomer)->create(['invoice_number' => 'INV-ZP-ADMIN-OTHER']);

    $this->actingAs($user)
        ->get('/zeropay/invoices')
        ->assertOk()
        ->assertSee('INV-ZP-ADMIN-OWN')
        ->assertDontSee('INV-ZP-ADMIN-OTHER');
});

test('bookkeeper sees only their organization invoices in zeropay', function () {
    [$user, $organization] = zeroPayFinanceUser('bookkeeper');

    $customer = Customer::factory()->create(['organization_id' => $organization->id]);
    Invoice::factory()->forCustomer($customer)->create(['invoice_number' => 'INV-ZP-BOOK-OWN']);

    $otherOrg = Organization::factory()->create();
    $otherCustomer = Customer::factory()->create(['organization_id' => $otherOrg->id]);
    Invoice::factory()->forCustomer($otherCustomer)->create(['invoice_number' => 'INV-ZP-BOOK-OTHER']);

    $this->actingAs($user)
        ->get('/zeropay/invoices')
        ->assertOk()
        ->assertSee('INV-ZP-BOOK-OWN')
        ->assertDontSee('INV-ZP-BOOK-OTHER');
});
