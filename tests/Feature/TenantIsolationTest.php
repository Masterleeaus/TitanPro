<?php

use App\Contracts\TenantAware;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Job;
use App\Models\JobType;
use App\Models\MessageTemplate;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Scopes\TenantScope;
use App\Models\User;
use App\Tenancy\CurrentTenant;
use App\Tenancy\TenantResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Request;

// ── Helpers ───────────────────────────────────────────────────────────────────

function isolationPair(string $role = 'owner'): array
{
    (new RolesAndPermissionsSeeder)->run();

    $orgA = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $orgA->id]);
    $userA->assignRole($role);

    $orgB = Organization::factory()->create();
    $userB = User::factory()->create(['organization_id' => $orgB->id]);
    $userB->assignRole($role);

    return [$userA, $orgA, $userB, $orgB];
}

// ── Container bindings ────────────────────────────────────────────────────────

test('TenantResolver is bound as a singleton', function () {
    $a = app(TenantResolver::class);
    $b = app(TenantResolver::class);

    expect($a)->toBeInstanceOf(TenantResolver::class)
        ->and($a)->toBe($b);
});

test('CurrentTenant is bound as a singleton', function () {
    $a = app(CurrentTenant::class);
    $b = app(CurrentTenant::class);

    expect($a)->toBeInstanceOf(CurrentTenant::class)
        ->and($a)->toBe($b);
});

test('CurrentTenant returns null when unauthenticated', function () {
    expect(app(CurrentTenant::class)->id())->toBeNull()
        ->and(app(CurrentTenant::class)->isResolved())->toBeFalse();
});

test('CurrentTenant returns authenticated users organisation_id', function () {
    (new RolesAndPermissionsSeeder)->run();
    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);

    $this->actingAs($user);

    expect(app(CurrentTenant::class)->id())->toBe($org->id)
        ->and(app(CurrentTenant::class)->isResolved())->toBeTrue();
});

// ── TenantResolver ────────────────────────────────────────────────────────────

test('TenantResolver resolves from authenticated user', function () {
    (new RolesAndPermissionsSeeder)->run();
    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);

    // Simulate auth login so that auth()->user() returns the user.
    $this->actingAs($user);

    // TenantResolver reads from auth()->user() on the current request.
    $resolved = app(TenantResolver::class)->resolve(app('request'));

    expect($resolved)->toBe($org->id);
});

test('TenantResolver returns null when no user is authenticated', function () {
    $resolved = app(TenantResolver::class)->resolve(app('request'));

    expect($resolved)->toBeNull();
});

// ── TenantScope on models ─────────────────────────────────────────────────────

test('TenantAware models have TenantScope registered', function (string $modelClass) {
    expect($modelClass)->toImplement(TenantAware::class);

    $scopes = (new $modelClass)->getGlobalScopes();
    expect($scopes)->toHaveKey(TenantScope::class);
})->with([
    Customer::class,
    Job::class,
    Invoice::class,
    Property::class,
    JobType::class,
    Estimate::class,
    Item::class,
    Payment::class,
    MessageTemplate::class,
]);

test('TenantScope is a no-op when unauthenticated', function () {
    $org = Organization::factory()->create();
    $customer = Customer::factory()->create(['organization_id' => $org->id]);

    // No actingAs — no authenticated user.
    $result = Customer::find($customer->id);

    // Scope should not block unauthenticated queries (safe for CLI / queues).
    expect($result)->not->toBeNull()
        ->and($result->id)->toBe($customer->id);
});

// ── Cross-tenant isolation: Eloquent queries ──────────────────────────────────

test('authenticated user only sees their own customers', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    Customer::factory()->count(3)->create(['organization_id' => $orgA->id]);
    Customer::factory()->count(5)->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA);

    $customers = Customer::all();

    expect($customers)->toHaveCount(3);
    expect($customers->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user only sees their own jobs', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    Job::factory()->count(2)->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    Job::factory()->count(4)->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $jobs = Job::all();

    expect($jobs)->toHaveCount(2);
    expect($jobs->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user only sees their own invoices', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    Invoice::factory()->count(2)->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    Invoice::factory()->count(3)->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $invoices = Invoice::all();

    expect($invoices)->toHaveCount(2);
    expect($invoices->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user cannot find a customer belonging to another org', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA);

    $found = Customer::find($customerB->id);

    expect($found)->toBeNull();
});

test('authenticated user cannot find a job belonging to another org', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobB = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $found = Job::find($jobB->id);

    expect($found)->toBeNull();
});

// ── withoutGlobalScope bypass ─────────────────────────────────────────────────

test('withoutGlobalScope(TenantScope) allows cross-tenant access', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA);

    // Simulate a super-admin / system context bypassing the scope.
    $found = Customer::withoutGlobalScope(TenantScope::class)->find($customerB->id);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($customerB->id);
});

// ── HTTP endpoint cross-tenant isolation ──────────────────────────────────────

test('org A user cannot view org B customer via HTTP', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    // With TenantScope active, route model binding returns 404 for resources
    // outside the current tenant — the record is simply not visible.  Either
    // 403 (explicit policy rejection) or 404 (not found via scope) confirms
    // that cross-tenant access is blocked.
    $response = $this->actingAs($userA)->get("/owner/customers/{$customerB->id}");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A user cannot view org B job via HTTP', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobB = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $response = $this->actingAs($userA)->get("/owner/jobs/{$jobB->id}");

    expect($response->getStatusCode())->toBeIn([403, 404]);
});

test('org A job index never includes org B jobs', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    Job::factory()->count(2)->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    Job::factory()->count(3)->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA)
        ->get('/owner/jobs')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('jobs.data', 2));
});

test('org A customer index never includes org B customers', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    Customer::factory()->count(2)->create(['organization_id' => $orgA->id]);
    Customer::factory()->count(4)->create(['organization_id' => $orgB->id]);

    $this->actingAs($userA)
        ->get('/owner/customers')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('customers.data', 2));
});
