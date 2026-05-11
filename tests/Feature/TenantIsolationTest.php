<?php

use App\Contracts\TenantAware;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\EstimatePackage;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Job;
use App\Models\JobMessage;
use App\Models\JobType;
use App\Models\JobTypeChecklistItem;
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
    JobMessage::class,
    EstimatePackage::class,
    JobTypeChecklistItem::class,
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

// ── Cross-tenant isolation: JobMessage ────────────────────────────────────────

test('authenticated user only sees their own job messages', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobA = Job::factory()->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    $jobB = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    JobMessage::factory()->count(2)->create(['organization_id' => $orgA->id, 'job_id' => $jobA->id, 'customer_id' => $customerA->id]);
    JobMessage::factory()->count(3)->create(['organization_id' => $orgB->id, 'job_id' => $jobB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $messages = JobMessage::all();

    expect($messages)->toHaveCount(2);
    expect($messages->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user cannot find a job message belonging to another org', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $jobB = Job::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $messageB = JobMessage::factory()->create(['organization_id' => $orgB->id, 'job_id' => $jobB->id, 'customer_id' => $customerB->id]);

    $this->actingAs($userA);

    $found = JobMessage::find($messageB->id);

    expect($found)->toBeNull();
});

test('job message organization_id is set automatically from auth user on create', function () {
    [$userA, $orgA] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $jobA = Job::factory()->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);

    $this->actingAs($userA);

    $message = JobMessage::withoutGlobalScope(TenantScope::class)->create([
        'job_id'      => $jobA->id,
        'customer_id' => $customerA->id,
        'channel'     => 'email',
        'event'       => 'job_scheduled',
        'recipient'   => 'test@example.com',
        'body'        => 'Test body',
        'status'      => 'sent',
    ]);

    expect($message->organization_id)->toBe($orgA->id);
});

// ── Cross-tenant isolation: EstimatePackage ───────────────────────────────────

test('authenticated user only sees their own estimate packages', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimateA = Estimate::factory()->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);

    EstimatePackage::factory()->count(2)->create(['organization_id' => $orgA->id, 'estimate_id' => $estimateA->id, 'tier' => 'good']);
    EstimatePackage::factory()->count(4)->create(['organization_id' => $orgB->id, 'estimate_id' => $estimateB->id, 'tier' => 'good']);

    $this->actingAs($userA);

    $packages = EstimatePackage::all();

    expect($packages)->toHaveCount(2);
    expect($packages->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user cannot find an estimate package belonging to another org', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);
    $estimateB = Estimate::factory()->create(['organization_id' => $orgB->id, 'customer_id' => $customerB->id]);
    $packageB = EstimatePackage::factory()->create(['organization_id' => $orgB->id, 'estimate_id' => $estimateB->id, 'tier' => 'good']);

    $this->actingAs($userA);

    $found = EstimatePackage::find($packageB->id);

    expect($found)->toBeNull();
});

test('estimate package organization_id is set automatically from auth user on create', function () {
    [$userA, $orgA] = isolationPair();

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $estimateA = Estimate::factory()->create(['organization_id' => $orgA->id, 'customer_id' => $customerA->id]);

    $this->actingAs($userA);

    $package = EstimatePackage::withoutGlobalScope(TenantScope::class)->create([
        'estimate_id' => $estimateA->id,
        'tier'        => 'better',
        'label'       => 'Standard',
        'subtotal'    => 500.00,
        'tax_amount'  => 50.00,
        'total'       => 550.00,
    ]);

    expect($package->organization_id)->toBe($orgA->id);
});

// ── Cross-tenant isolation: JobTypeChecklistItem ──────────────────────────────

test('authenticated user only sees their own job type checklist items', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $jobTypeA = JobType::factory()->create(['organization_id' => $orgA->id]);
    $jobTypeB = JobType::factory()->create(['organization_id' => $orgB->id]);

    JobTypeChecklistItem::factory()->count(2)->create(['organization_id' => $orgA->id, 'job_type_id' => $jobTypeA->id]);
    JobTypeChecklistItem::factory()->count(5)->create(['organization_id' => $orgB->id, 'job_type_id' => $jobTypeB->id]);

    $this->actingAs($userA);

    $items = JobTypeChecklistItem::all();

    expect($items)->toHaveCount(2);
    expect($items->pluck('organization_id')->unique()->all())->toBe([$orgA->id]);
});

test('authenticated user cannot find a job type checklist item belonging to another org', function () {
    [$userA, $orgA, $userB, $orgB] = isolationPair();

    $jobTypeB = JobType::factory()->create(['organization_id' => $orgB->id]);
    $itemB = JobTypeChecklistItem::factory()->create(['organization_id' => $orgB->id, 'job_type_id' => $jobTypeB->id]);

    $this->actingAs($userA);

    $found = JobTypeChecklistItem::find($itemB->id);

    expect($found)->toBeNull();
});

test('job type checklist item organization_id is set automatically from auth user on create', function () {
    [$userA, $orgA] = isolationPair();

    $jobTypeA = JobType::factory()->create(['organization_id' => $orgA->id]);

    $this->actingAs($userA);

    $item = JobTypeChecklistItem::withoutGlobalScope(TenantScope::class)->create([
        'job_type_id' => $jobTypeA->id,
        'label'       => 'Test task',
        'sort_order'  => 0,
        'is_required' => false,
    ]);

    expect($item->organization_id)->toBe($orgA->id);
});

