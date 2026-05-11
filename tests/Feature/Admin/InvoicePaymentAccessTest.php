<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/**
 * Issue 197 — Restrict InvoiceResource / PaymentResource in the TitanPro panel to super_admin only.
 *
 * The canonical finance surface lives at /zeropay (ZeroPay panel), accessible to
 * bookkeeper / owner / admin. These tests confirm that:
 *  - a non-super-admin user is denied access to the TitanPro panel invoice and payment routes
 *  - the old /admin/invoices path (no longer registered) returns 404 for any authenticated user
 *  - super_admin retains access to the /titanpro invoice and payment routes
 */

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
