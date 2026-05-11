<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Permission;

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Create an authenticated user with the given Spatie permission assigned
 * directly (no role required) so we can test permission-only access.
 */
function uiInspectorUserWithPermission(string $permission): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->givePermissionTo($permission);

    return $user;
}

/**
 * Create an authenticated user with no role and no special permissions.
 */
function uiInspectorUserWithoutPermission(): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();

    return User::factory()->create(['organization_id' => $org->id]);
}

// ── Permission allows access (no admin role) ──────────────────────────────────

test('user with ui-inspector.manage permission can GET overrides', function () {
    $user = uiInspectorUserWithPermission('ui-inspector.manage');

    $this->actingAs($user)
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});

test('user with ui-inspector.manage permission can POST overrides', function () {
    $user = uiInspectorUserWithPermission('ui-inspector.manage');

    $this->actingAs($user)
        ->postJson('/titan/ui-inspector/overrides', [
            'component_key' => 'btn-primary',
            'properties'    => ['color' => '#ff0000'],
        ])
        ->assertOk();
});

test('user with ui-inspector.manage permission can DELETE a single override', function () {
    $user = uiInspectorUserWithPermission('ui-inspector.manage');

    $this->actingAs($user)
        ->deleteJson('/titan/ui-inspector/overrides/btn-primary')
        ->assertOk()
        ->assertJson(['reset' => true]);
});

test('user with ui-inspector.manage permission can DELETE all overrides', function () {
    $user = uiInspectorUserWithPermission('ui-inspector.manage');

    $this->actingAs($user)
        ->deleteJson('/titan/ui-inspector/overrides')
        ->assertOk()
        ->assertJson(['reset_all' => true]);
});

// ── No permission → 403 ───────────────────────────────────────────────────────

test('authenticated user without ui-inspector.manage permission gets 403 on GET', function () {
    $user = uiInspectorUserWithoutPermission();

    $this->actingAs($user)
        ->getJson('/titan/ui-inspector/overrides')
        ->assertForbidden();
});

test('authenticated user without ui-inspector.manage permission gets 403 on POST', function () {
    $user = uiInspectorUserWithoutPermission();

    $this->actingAs($user)
        ->postJson('/titan/ui-inspector/overrides', [
            'component_key' => 'btn-primary',
            'properties'    => ['color' => '#ff0000'],
        ])
        ->assertForbidden();
});

test('authenticated user without ui-inspector.manage permission gets 403 on DELETE', function () {
    $user = uiInspectorUserWithoutPermission();

    $this->actingAs($user)
        ->deleteJson('/titan/ui-inspector/overrides/btn-primary')
        ->assertForbidden();
});

// ── Unauthenticated → redirect ────────────────────────────────────────────────

test('unauthenticated user is redirected from GET overrides', function () {
    $this->getJson('/titan/ui-inspector/overrides')
        ->assertUnauthorized();
});

// ── Privileged roles still have access ───────────────────────────────────────

test('super_admin role can GET overrides', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});

test('admin role can GET overrides', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('admin');

    $this->actingAs($user)
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});

test('owner role can GET overrides', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    $this->actingAs($user)
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});
