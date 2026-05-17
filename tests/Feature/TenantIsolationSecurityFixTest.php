<?php

/**
 * Tenant isolation tests for models added in the BelongsToTenant security fix.
 *
 * Verifies that DriverLocation, Subscription, TitanUsageMeter, Attachment,
 * JobChecklistItem, OrganizationSetting, UiOverride, and TitanUiComponentOverride
 * all carry the BelongsToTenant trait and are correctly scoped per tenant.
 *
 * Acceptance criteria (issue — "Core app models missing BelongsToTenant"):
 *   - All 8 models carry BelongsToTenant trait
 *   - DriverLocation::all() for org A user returns zero rows owned by org B
 *   - OrganizationSetting scoped read never exposes another org's API keys
 *   - OrganizationSetting.stripe_publishable_key and .twilio_account_sid have encrypted cast
 */

use App\Contracts\TenantAware;
use App\Models\Attachment;
use App\Models\DriverLocation;
use App\Models\JobChecklistItem;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\Scopes\TenantScope;
use App\Models\Subscription;
use App\Models\TitanUiComponentOverride;
use App\Models\TitanUsageMeter;
use App\Models\UiOverride;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\DB;

// ── Helper ────────────────────────────────────────────────────────────────────

function tenantFixturePair(string $role = 'owner'): array
{
    (new RolesAndPermissionsSeeder)->run();

    $orgA  = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole($role);

    $orgB  = Organization::factory()->create();
    $userB = User::factory()->create(['organization_id' => $orgB->id]);
    $userB->assignRole($role);

    return [$userA, $orgA, $userB, $orgB];
}

// ═════════════════════════════════════════════════════════════════════════════
// PART 1 — All 8 models implement TenantAware and carry TenantScope
// ═════════════════════════════════════════════════════════════════════════════

test('DriverLocation implements TenantAware and has TenantScope', function () {
    expect(DriverLocation::class)->toImplement(TenantAware::class);
    expect((new DriverLocation)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('Subscription implements TenantAware and has TenantScope', function () {
    expect(Subscription::class)->toImplement(TenantAware::class);
    expect((new Subscription)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('TitanUsageMeter implements TenantAware and has TenantScope', function () {
    expect(TitanUsageMeter::class)->toImplement(TenantAware::class);
    expect((new TitanUsageMeter)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('Attachment implements TenantAware and has TenantScope', function () {
    expect(Attachment::class)->toImplement(TenantAware::class);
    expect((new Attachment)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('JobChecklistItem implements TenantAware and has TenantScope', function () {
    expect(JobChecklistItem::class)->toImplement(TenantAware::class);
    expect((new JobChecklistItem)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('OrganizationSetting implements TenantAware and has TenantScope', function () {
    expect(OrganizationSetting::class)->toImplement(TenantAware::class);
    expect((new OrganizationSetting)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('UiOverride implements TenantAware and has TenantScope', function () {
    expect(UiOverride::class)->toImplement(TenantAware::class);
    expect((new UiOverride)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

test('TitanUiComponentOverride implements TenantAware and has TenantScope', function () {
    expect(TitanUiComponentOverride::class)->toImplement(TenantAware::class);
    expect((new TitanUiComponentOverride)->getGlobalScopes())->toHaveKey(TenantScope::class);
});

// ═════════════════════════════════════════════════════════════════════════════
// PART 2 — DriverLocation cross-tenant query isolation
// ═════════════════════════════════════════════════════════════════════════════

test('DriverLocation::all() for org A user returns zero rows owned by org B', function () {
    [$userA, $orgA, $userB, $orgB] = tenantFixturePair();

    // Create a location row owned by org B.
    DriverLocation::withoutGlobalScope(TenantScope::class)->create([
        'organization_id' => $orgB->id,
        'user_id'         => $userB->id,
        'latitude'        => 40.7128,
        'longitude'       => -74.0060,
        'recorded_at'     => now(),
    ]);

    // User A should see zero locations (none belong to org A).
    $this->actingAs($userA);

    $results = DriverLocation::all();

    expect($results)->toHaveCount(0);
});

test('DriverLocation::all() for org A user returns only org A rows', function () {
    [$userA, $orgA, $userB, $orgB] = tenantFixturePair();

    // Org A gets 2 locations, org B gets 3.
    foreach (range(1, 2) as $_) {
        DriverLocation::withoutGlobalScope(TenantScope::class)->create([
            'organization_id' => $orgA->id,
            'user_id'         => $userA->id,
            'latitude'        => 51.5074,
            'longitude'       => -0.1278,
            'recorded_at'     => now(),
        ]);
    }
    foreach (range(1, 3) as $_) {
        DriverLocation::withoutGlobalScope(TenantScope::class)->create([
            'organization_id' => $orgB->id,
            'user_id'         => $userB->id,
            'latitude'        => 40.7128,
            'longitude'       => -74.0060,
            'recorded_at'     => now(),
        ]);
    }

    $this->actingAs($userA);

    $results = DriverLocation::all();

    expect($results)->toHaveCount(2);
    expect($results->pluck('organization_id')->unique()->values()->all())->toBe([$orgA->id]);
});

// ═════════════════════════════════════════════════════════════════════════════
// PART 3 — OrganizationSetting cross-tenant isolation
// ═════════════════════════════════════════════════════════════════════════════

test('OrganizationSetting scoped read never exposes another orgs API keys', function () {
    [$userA, $orgA, $userB, $orgB] = tenantFixturePair();

    // Create settings for both orgs directly (bypass scope to seed both rows).
    OrganizationSetting::withoutGlobalScope(TenantScope::class)->create([
        'organization_id'   => $orgA->id,
        'stripe_secret_key' => 'sk_orgA_secret',
    ]);
    OrganizationSetting::withoutGlobalScope(TenantScope::class)->create([
        'organization_id'   => $orgB->id,
        'stripe_secret_key' => 'sk_orgB_secret',
    ]);

    // Authenticate as org A user and query OrganizationSetting.
    $this->actingAs($userA);

    $settings = OrganizationSetting::all();

    // User A must see exactly one row — their own.
    expect($settings)->toHaveCount(1);
    expect($settings->first()->organization_id)->toBe($orgA->id);

    // The org B key must not appear in any returned row.
    expect($settings->pluck('stripe_secret_key')->contains('sk_orgB_secret'))->toBeFalse();
});

test('OrganizationSetting find returns null when requesting another orgs record', function () {
    [$userA, $orgA, , $orgB] = tenantFixturePair();

    $settingB = OrganizationSetting::withoutGlobalScope(TenantScope::class)->create([
        'organization_id' => $orgB->id,
    ]);

    $this->actingAs($userA);

    expect(OrganizationSetting::find($settingB->id))->toBeNull();
});

// ═════════════════════════════════════════════════════════════════════════════
// PART 4 — OrganizationSetting encryption casts
// ═════════════════════════════════════════════════════════════════════════════

test('OrganizationSetting stripe_publishable_key has encrypted cast', function () {
    $casts = (new OrganizationSetting)->getCasts();

    expect($casts)->toHaveKey('stripe_publishable_key');
    expect($casts['stripe_publishable_key'])->toBe('encrypted');
});

test('OrganizationSetting twilio_account_sid has encrypted cast', function () {
    $casts = (new OrganizationSetting)->getCasts();

    expect($casts)->toHaveKey('twilio_account_sid');
    expect($casts['twilio_account_sid'])->toBe('encrypted');
});

test('OrganizationSetting stripe_publishable_key is stored encrypted in the database', function () {
    [$userA, $orgA] = tenantFixturePair();

    $this->actingAs($userA);

    OrganizationSetting::create([
        'organization_id'        => $orgA->id,
        'stripe_publishable_key' => 'pk_test_plain_value',
    ]);

    $rawValue = DB::table('organization_settings')
        ->where('organization_id', $orgA->id)
        ->value('stripe_publishable_key');

    expect($rawValue)->not->toContain('pk_test_plain_value');
    expect($rawValue)->not->toBeNull();
});

test('OrganizationSetting twilio_account_sid is stored encrypted in the database', function () {
    [$userA, $orgA] = tenantFixturePair();

    $this->actingAs($userA);

    OrganizationSetting::create([
        'organization_id'  => $orgA->id,
        'twilio_account_sid' => 'AC_plain_test_sid',
    ]);

    $rawValue = DB::table('organization_settings')
        ->where('organization_id', $orgA->id)
        ->value('twilio_account_sid');

    expect($rawValue)->not->toContain('AC_plain_test_sid');
    expect($rawValue)->not->toBeNull();
});
