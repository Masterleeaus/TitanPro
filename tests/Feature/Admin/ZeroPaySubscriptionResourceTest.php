<?php

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PlanService;
use Database\Seeders\RolesAndPermissionsSeeder;

function zeroPayBookkeeper(): array
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('bookkeeper');

    // OrganizationFactory already creates a trialing subscription; retrieve it.
    $subscription = $org->subscriptions()->firstOrFail();

    return [$user, $org, $subscription];
}

test('bookkeeper can list zeropay subscriptions with formatted amount', function () {
    [$user, , $subscription] = zeroPayBookkeeper();

    $subscription->update([
        'plan' => PlanService::PLAN_GROWTH,
        'billing_interval' => 'monthly',
    ]);

    $this->actingAs($user)
        ->get('/zeropay/subscriptions')
        ->assertOk()
        ->assertSeeText('Amount')
        ->assertSeeText('$149.00');
});

test('bookkeeper can view their org subscription', function () {
    [$user, , $subscription] = zeroPayBookkeeper();

    $this->actingAs($user)
        ->get("/zeropay/subscriptions/{$subscription->id}")
        ->assertOk();
});

test('bookkeeper gets 404 when viewing a cross-org subscription', function () {
    [$user] = zeroPayBookkeeper();

    $otherOrg          = Organization::factory()->create();
    $otherSubscription = $otherOrg->subscriptions()->firstOrFail();

    $this->actingAs($user)
        ->get("/zeropay/subscriptions/{$otherSubscription->id}")
        ->assertNotFound();
});

test('owner can list zeropay subscriptions', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/zeropay/subscriptions')
        ->assertOk();
});

test('admin can list zeropay subscriptions', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/zeropay/subscriptions')
        ->assertOk();
});
