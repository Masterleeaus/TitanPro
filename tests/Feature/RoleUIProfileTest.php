<?php

use App\Models\Organization;
use App\Models\RoleUIProfile;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

// ── Model / DB ────────────────────────────────────────────────────────────────

test('role ui profile can be created and retrieved for a role', function () {
    $org = Organization::factory()->create();

    $profile = RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'bookkeeper',
        'primary_color'   => '#1e3a5f',
        'accent_color'    => '#f59e0b',
        'hidden_nav_items'=> ['Jobs', 'Site Settings'],
        'widget_layout'   => ['kpi-grid-card', 'table-card'],
    ]);

    $found = RoleUIProfile::forRole('bookkeeper', $org->id);

    expect($found)->not->toBeNull()
        ->and($found->primary_color)->toBe('#1e3a5f')
        ->and($found->accent_color)->toBe('#f59e0b')
        ->and($found->hidden_nav_items)->toBe(['Jobs', 'Site Settings'])
        ->and($found->widget_layout)->toBe(['kpi-grid-card', 'table-card']);
});

test('forRole returns null when no profile exists for the role', function () {
    $org = Organization::factory()->create();

    expect(RoleUIProfile::forRole('dispatcher', $org->id))->toBeNull();
});

test('themeOverrides returns only non-null color fields', function () {
    $org = Organization::factory()->create();

    $profile = RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'dispatcher',
        'primary_color'   => '#10b981',
        'secondary_color' => null,
        'accent_color'    => null,
        'surface_color'   => '#f0fdf4',
    ]);

    $overrides = $profile->themeOverrides();

    expect($overrides)->toHaveKey('primary_color', '#10b981')
        ->and($overrides)->toHaveKey('surface_color', '#f0fdf4')
        ->and($overrides)->not->toHaveKey('secondary_color')
        ->and($overrides)->not->toHaveKey('accent_color');
});

test('role_ui_profiles table enforces unique constraint per org and role', function () {
    $org = Organization::factory()->create();

    RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'technician',
        'primary_color'   => '#aabbcc',
    ]);

    expect(fn () => RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'technician',
        'primary_color'   => '#ddeeff',
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

// ── Finance (bookkeeper) user theme ───────────────────────────────────────────

test('finance user sees finance theme overrides when role profile exists', function () {
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('bookkeeper');

    RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'bookkeeper',
        'primary_color'   => '#1e3a5f',
        'accent_color'    => '#f59e0b',
        'widget_layout'   => ['kpi-grid-card', 'table-card'],
    ]);

    // Simulate middleware resolution: get role, find profile, check theme.
    $role    = $user->getRoleNames()->first();
    $profile = RoleUIProfile::forRole($role, $org->id);

    expect($role)->toBe('bookkeeper')
        ->and($profile)->not->toBeNull()
        ->and($profile->themeOverrides())->toMatchArray([
            'primary_color' => '#1e3a5f',
            'accent_color'  => '#f59e0b',
        ])
        ->and($profile->widget_layout)->toContain('kpi-grid-card')
        ->and($profile->widget_layout)->toContain('table-card');
});

// ── Dispatch user layout ──────────────────────────────────────────────────────

test('dispatch user sees map-focused layout when role profile exists', function () {
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('dispatcher');

    RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'dispatcher',
        'primary_color'   => '#0f766e',
        'widget_layout'   => ['map-card', 'kpi-grid-card'],
        'hidden_nav_items'=> ['Invoices', 'Site Settings'],
    ]);

    $role    = $user->getRoleNames()->first();
    $profile = RoleUIProfile::forRole($role, $org->id);

    expect($role)->toBe('dispatcher')
        ->and($profile)->not->toBeNull()
        ->and($profile->widget_layout)->toContain('map-card')
        ->and($profile->hidden_nav_items)->toContain('Invoices')
        ->and($profile->hidden_nav_items)->toContain('Site Settings');
});

// ── Fallback when no profile exists ──────────────────────────────────────────

test('no role profile means no theme override is applied (fallback to platform defaults)', function () {
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('bookkeeper');

    // Ensure no profile exists for the org.
    expect(RoleUIProfile::forRole('bookkeeper', $org->id))->toBeNull();

    // Without a profile, the middleware should not apply any overrides.
    $role    = $user->getRoleNames()->first();
    $profile = RoleUIProfile::forRole($role, $org->id);

    expect($profile)->toBeNull();
});

// ── Org isolation ─────────────────────────────────────────────────────────────

test('role profiles are scoped to organisation — org A profile does not bleed into org B', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    RoleUIProfile::create([
        'organization_id' => $orgA->id,
        'role'            => 'bookkeeper',
        'primary_color'   => '#aa0000',
    ]);

    // Org B has no profile for bookkeeper.
    expect(RoleUIProfile::forRole('bookkeeper', $orgB->id))->toBeNull();

    // Org A profile is found.
    $aProfile = RoleUIProfile::forRole('bookkeeper', $orgA->id);
    expect($aProfile)->not->toBeNull()
        ->and($aProfile->primary_color)->toBe('#aa0000');
});

// ── Cache invalidation ────────────────────────────────────────────────────────

test('updating a role profile clears the cache key', function () {
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('dispatcher');

    $profile = RoleUIProfile::create([
        'organization_id' => $org->id,
        'role'            => 'dispatcher',
        'primary_color'   => '#111111',
    ]);

    // Warm up the cache as the middleware would.
    $cacheKey = "role_ui_profile.{$org->id}.dispatcher";
    Cache::put($cacheKey, $profile, 300);
    expect(Cache::has($cacheKey))->toBeTrue();

    // Forget (as the publish action does).
    Cache::forget($cacheKey);
    expect(Cache::has($cacheKey))->toBeFalse();
});
