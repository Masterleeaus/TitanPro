<?php

use App\Models\Customer;
use App\Models\Item;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Organization;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

// Helper: create an org-scoped user and assign a role
function adminTestUser(string $role): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole($role);

    return $user;
}

// ── Legacy /admin alias ───────────────────────────────────────────────────────

test('unauthenticated users hitting /admin are permanently redirected to /titanpro', function () {
    $this->get('/admin')->assertRedirect('/titanpro');
});

// ── TitanPro panel authentication gate ───────────────────────────────────────

test('titanpro login page renders', function () {
    $this->get('/titanpro/login')->assertOk();
});

// ── Role-based access: allowed role ──────────────────────────────────────────

test('super_admin can access the titanpro panel', function () {
    $this->actingAs(adminTestUser('super_admin'))->get('/titanpro')->assertOk();
});

// ── Role-based access: denied roles ──────────────────────────────────────────

test('admin role cannot access the titanpro panel', function () {
    $this->actingAs(adminTestUser('admin'))->get('/titanpro')->assertForbidden();
});

test('owner role cannot access the titanpro panel', function () {
    $this->actingAs(adminTestUser('owner'))->get('/titanpro')->assertForbidden();
});

test('dispatcher cannot access the titanpro panel', function () {
    $this->actingAs(adminTestUser('dispatcher'))->get('/titanpro')->assertForbidden();
});

test('technician cannot access the titanpro panel', function () {
    $this->actingAs(adminTestUser('technician'))->get('/titanpro')->assertForbidden();
});

test('bookkeeper cannot access the titanpro panel', function () {
    $this->actingAs(adminTestUser('bookkeeper'))->get('/titanpro')->assertForbidden();
});

test('authenticated user with no role cannot access the titanpro panel', function () {
    (new RolesAndPermissionsSeeder)->run();
    $user = User::factory()->create();

    $this->actingAs($user)->get('/titanpro')->assertForbidden();
});
