<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Create an org-scoped user and give them the named permission directly
 * (no role required) to test permission-only access.
 */
function uiInspectorUserWithPermission(string $permission): User
{
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->givePermissionTo($permission);

    return $user;
}

/**
 * Create an org-scoped user with no roles and no special permissions.
 */
function uiInspectorUserWithoutPermission(): User
{
    $org = Organization::factory()->create();

    return User::factory()->create(['organization_id' => $org->id]);
}

/**
 * Create an org-scoped user and assign the given role.
 */
function uiInspectorUserWithRole(string $role): User
{
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole($role);

    return $user;
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

// ── Unauthenticated → 401 ────────────────────────────────────────────────────

test('unauthenticated user gets 401 on GET overrides', function () {
    $this->getJson('/titan/ui-inspector/overrides')
        ->assertUnauthorized();
});

// ── Privileged roles still have access (backward compatibility) ───────────────

test('super_admin role can GET overrides', function () {
    $this->actingAs(uiInspectorUserWithRole('super_admin'))
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});

test('admin role can GET overrides', function () {
    $this->actingAs(uiInspectorUserWithRole('admin'))
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});

test('owner role can GET overrides', function () {
    $this->actingAs(uiInspectorUserWithRole('owner'))
        ->getJson('/titan/ui-inspector/overrides')
        ->assertOk();
});
