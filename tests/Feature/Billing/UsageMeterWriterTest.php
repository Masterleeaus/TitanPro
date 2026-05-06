<?php

use App\Events\UsageLimitApproaching;
use App\Events\UsageLimitExceeded;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\TitanUsageMeter;
use App\Models\User;
use App\Services\UsageMeterWriter;
use App\Services\PlanResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => (new RolesAndPermissionsSeeder)->run());

// ── UsageMeterWriter::increment ───────────────────────────────────────────────

test('increment creates a meter record for the current period', function () {
    $org    = Organization::factory()->create();
    $writer = app(UsageMeterWriter::class);

    $count = $writer->increment($org, 'cleaning_jobs');

    expect($count)->toBe(1);

    $this->assertDatabaseHas('titan_usage_meters', [
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 1,
    ]);
});

test('increment accumulates count across multiple calls', function () {
    $org    = Organization::factory()->create();
    $writer = app(UsageMeterWriter::class);

    $writer->increment($org, 'cleaning_jobs');
    $writer->increment($org, 'cleaning_jobs');
    $count = $writer->increment($org, 'cleaning_jobs');

    expect($count)->toBe(3);

    $this->assertDatabaseHas('titan_usage_meters', [
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'count'           => 3,
    ]);
});

test('increment fires UsageLimitApproaching at 80% of limit', function () {
    Event::fake([UsageLimitApproaching::class, UsageLimitExceeded::class]);

    // growth plan has cleaning_jobs limit of 1000; 80% = 800
    $org = Organization::factory()->create(['plan' => 'growth']);
    Subscription::factory()->active($org, 'growth')->create();

    $meter = TitanUsageMeter::create([
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 799,
        'reset_at'        => now()->startOfMonth()->addMonth(),
    ]);

    $writer = app(UsageMeterWriter::class);
    $writer->increment($org, 'cleaning_jobs');

    Event::assertDispatched(UsageLimitApproaching::class, function ($event) use ($org) {
        return $event->organization->id === $org->id
            && $event->meterKey === 'cleaning_jobs'
            && $event->count === 800
            && $event->limit === 1000;
    });
    Event::assertNotDispatched(UsageLimitExceeded::class);
});

test('increment fires UsageLimitExceeded at 100% of limit', function () {
    Event::fake([UsageLimitApproaching::class, UsageLimitExceeded::class]);

    // starter plan has cleaning_jobs limit of 250
    $org = Organization::factory()->create(['plan' => 'starter']);
    Subscription::factory()->active($org, 'starter')->create();

    TitanUsageMeter::create([
        'organization_id' => $org->id,
        'meter_key'       => 'cleaning_jobs',
        'period'          => now()->format('Y-m'),
        'count'           => 249,
        'reset_at'        => now()->startOfMonth()->addMonth(),
    ]);

    $writer = app(UsageMeterWriter::class);
    $writer->increment($org, 'cleaning_jobs');

    Event::assertDispatched(UsageLimitExceeded::class, function ($event) use ($org) {
        return $event->organization->id === $org->id
            && $event->meterKey === 'cleaning_jobs'
            && $event->count === 250
            && $event->limit === 250;
    });
});

test('increment does not fire events when plan has unlimited meters', function () {
    Event::fake([UsageLimitApproaching::class, UsageLimitExceeded::class]);

    // pro plan has cleaning_jobs limit of null (unlimited)
    $org = Organization::factory()->create(['plan' => 'pro']);
    Subscription::factory()->active($org, 'pro')->create();

    $writer = app(UsageMeterWriter::class);
    for ($i = 0; $i < 10; $i++) {
        $writer->increment($org, 'cleaning_jobs');
    }

    Event::assertNotDispatched(UsageLimitApproaching::class);
    Event::assertNotDispatched(UsageLimitExceeded::class);
});

// ── PlanResolver ──────────────────────────────────────────────────────────────

test('PlanResolver resolves the active plan for an org', function () {
    $org = Organization::factory()->create(['plan' => 'growth']);
    Subscription::factory()->active($org, 'growth')->create();

    $resolver = app(PlanResolver::class);
    expect($resolver->resolve($org))->toBe('growth');
});

test('PlanResolver limitFor returns correct limit for starter plan', function () {
    $org = Organization::factory()->create(['plan' => 'starter']);
    Subscription::factory()->active($org, 'starter')->create();

    $resolver = app(PlanResolver::class);
    expect($resolver->limitFor($org, 'cleaning_jobs'))->toBe(250);
});

test('PlanResolver limitFor returns null for pro plan (unlimited)', function () {
    $org = Organization::factory()->create(['plan' => 'pro']);
    Subscription::factory()->active($org, 'pro')->create();

    $resolver = app(PlanResolver::class);
    expect($resolver->limitFor($org, 'cleaning_jobs'))->toBeNull();
});

test('PlanResolver limitFor returns null for unknown meter key', function () {
    $org = Organization::factory()->create(['plan' => 'growth']);
    Subscription::factory()->active($org, 'growth')->create();

    $resolver = app(PlanResolver::class);
    expect($resolver->limitFor($org, 'unknown_meter'))->toBeNull();
});
