<?php

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\TitanUsageMeter;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Route;

beforeEach(fn () => (new RolesAndPermissionsSeeder)->run());

// Register a test route protected by the billing limit middleware
beforeEach(function () {
    Route::middleware(['web', 'auth', 'titan.billing.limit:cleaning_jobs'])
        ->get('/test-billing-limit', fn () => response()->json(['ok' => true]))
        ->name('test.billing.limit');
});

// ── Requests within limit are allowed ────────────────────────────────────────

test('request is allowed when usage is below the plan limit', function () {
    $org  = Organization::factory()->create(['plan' => 'starter']);
    $user = User::factory()->owner($org)->create();
    Subscription::factory()->active($org, 'starter')->create();

    // 249 out of 250 — one slot remaining
    TitanUsageMeter::create([
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 249,
        'reset_at'        => now()->startOfMonth()->addMonth(),
    ]);

    $this->actingAs($user)
        ->getJson('/test-billing-limit')
        ->assertOk();
});

test('request is allowed when no meter record exists yet', function () {
    $org  = Organization::factory()->create(['plan' => 'starter']);
    $user = User::factory()->owner($org)->create();
    Subscription::factory()->active($org, 'starter')->create();

    $this->actingAs($user)
        ->getJson('/test-billing-limit')
        ->assertOk();
});

// ── Requests over limit return 402 ───────────────────────────────────────────

test('request is blocked with 402 when usage equals the plan limit', function () {
    $org  = Organization::factory()->create(['plan' => 'starter']);
    $user = User::factory()->owner($org)->create();
    Subscription::factory()->active($org, 'starter')->create();

    // Exactly at limit (250)
    TitanUsageMeter::create([
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 250,
        'reset_at'        => now()->startOfMonth()->addMonth(),
    ]);

    $this->actingAs($user)
        ->getJson('/test-billing-limit')
        ->assertStatus(402)
        ->assertJsonStructure(['message', 'meter_key', 'limit', 'count']);
});

test('request is blocked with 402 when usage exceeds the plan limit', function () {
    $org  = Organization::factory()->create(['plan' => 'growth']);
    $user = User::factory()->owner($org)->create();
    Subscription::factory()->active($org, 'growth')->create();

    TitanUsageMeter::create([
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 1001,
        'reset_at'        => now()->startOfMonth()->addMonth(),
    ]);

    $response = $this->actingAs($user)
        ->getJson('/test-billing-limit')
        ->assertStatus(402);

    expect($response->json('limit'))->toBe(1000);
    expect($response->json('count'))->toBe(1001);
    expect($response->json('meter_key'))->toBe('cleaning_jobs');
});

// ── Unlimited plan is never blocked ──────────────────────────────────────────

test('pro plan with unlimited meter is never blocked', function () {
    $org  = Organization::factory()->create(['plan' => 'pro']);
    $user = User::factory()->owner($org)->create();
    Subscription::factory()->active($org, 'pro')->create();

    TitanUsageMeter::create([
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 99999,
        'reset_at'        => now()->startOfMonth()->addMonth(),
    ]);

    $this->actingAs($user)
        ->getJson('/test-billing-limit')
        ->assertOk();
});

// ── Unauthenticated requests pass through ────────────────────────────────────

test('unauthenticated request passes through the billing limit middleware', function () {
    $this->getJson('/test-billing-limit')
        ->assertUnauthorized();
});
