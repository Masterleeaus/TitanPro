<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Gate;

/**
 * Verifies that the `titan.admin` gate and `module.admin` middleware
 * protect module administration routes correctly.
 */
beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

// ── Gate: titan.admin ─────────────────────────────────────────────────────────

test('titan.admin gate allows super_admin role', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user);

    expect(Gate::allows('titan.admin'))->toBeTrue();
});

test('titan.admin gate denies regular owner role', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user);

    expect(Gate::allows('titan.admin'))->toBeFalse();
});

test('titan.admin gate denies admin role', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user);

    expect(Gate::allows('titan.admin'))->toBeFalse();
});

test('titan.admin gate denies technician role', function () {
    $user = User::factory()->create();
    $user->assignRole('technician');

    $this->actingAs($user);

    expect(Gate::allows('titan.admin'))->toBeFalse();
});

test('titan.admin gate denies unauthenticated (guest)', function () {
    expect(Gate::forUser(null)->allows('titan.admin'))->toBeFalse();
});

// ── EnsureModuleAdmin middleware via route ────────────────────────────────────

test('module.admin middleware allows super_admin to access audit log', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get('/platform/modules/audit-log')
        ->assertOk();
});

test('module.admin middleware returns 403 for owner role', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/platform/modules/audit-log')
        ->assertForbidden();
});

test('module.admin middleware returns 403 for technician role', function () {
    $user = User::factory()->create();
    $user->assignRole('technician');

    $this->actingAs($user)
        ->get('/platform/modules/audit-log')
        ->assertForbidden();
});

test('module.admin middleware redirects unauthenticated users to login', function () {
    $this->get('/platform/modules/audit-log')
        ->assertRedirect();
});
