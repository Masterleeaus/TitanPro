<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

function groundZeroUser(string $role): User
{
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole($role);

    return $user;
}

// ── Role-based access ────────────────────────────────────────────────────────

test('owner can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('owner'))->get('/groundzero')->assertOk();
});

test('admin can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('admin'))->get('/groundzero')->assertOk();
});

test('dispatcher can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('dispatcher'))->get('/groundzero')->assertOk();
});

test('bookkeeper can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('bookkeeper'))->get('/groundzero')->assertOk();
});

test('technician cannot access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('technician'))->get('/groundzero')->assertForbidden();
});

test('owner user lands on the GroundZero dashboard after login', function () {
    $this->actingAs(groundZeroUser('owner'))
        ->followingRedirects()
        ->get('/groundzero')
        ->assertOk();
});

// ── Brand name ───────────────────────────────────────────────────────────────

test('GroundZero panel renders brand name as GroundZero', function () {
    $this->actingAs(groundZeroUser('owner'))
        ->followingRedirects()
        ->get('/groundzero')
        ->assertSee('GroundZero');
});

// ── Subscription gating (CheckSubscription middleware) ───────────────────────

test('owner without an active subscription is redirected by CheckSubscription', function () {
    $org  = Organization::factory()->withoutSubscription()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/groundzero')
        ->assertRedirect(route('owner.subscription.expired'));
});

test('admin without an active subscription is redirected by CheckSubscription', function () {
    $org  = Organization::factory()->withoutSubscription()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/groundzero')
        ->assertRedirect(route('owner.subscription.expired'));
});

test('dispatcher passes through CheckSubscription even without an active subscription', function () {
    $org  = Organization::factory()->withoutSubscription()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('dispatcher');

    $this->actingAs($user)
        ->followingRedirects()
        ->get('/groundzero')
        ->assertOk();
});

test('bookkeeper passes through CheckSubscription even without an active subscription', function () {
    $org  = Organization::factory()->withoutSubscription()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('bookkeeper');

    $this->actingAs($user)
        ->followingRedirects()
        ->get('/groundzero')
        ->assertOk();
});
