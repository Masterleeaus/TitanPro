<?php

use App\Models\Organization;
use App\Models\User;
use App\Support\CleaningAdminMetrics;
use Database\Seeders\DemoSeeder;

test('demo seeder keeps demo users scoped to the demo organization', function () {
    $otherOrg = Organization::factory()->create();

    User::factory()->create([
        'email' => 'admin@demo.test',
        'organization_id' => $otherOrg->id,
    ]);

    (new DemoSeeder)->run();

    $demoOrg = Organization::query()->where('slug', 'demo-fieldops')->firstOrFail();
    $demoAdmin = User::query()->where('email', 'admin@demo.test')->firstOrFail();

    expect($demoAdmin->organization_id)->toBe($demoOrg->id);
});

test('demo seeder provides non-zero dashboard metrics for the demo organization', function () {
    (new DemoSeeder)->run();

    $demoAdmin = User::query()->where('email', 'admin@demo.test')->firstOrFail();
    $this->actingAs($demoAdmin);

    $totals = CleaningAdminMetrics::dashboardTotals();

    expect($totals['jobs_today'])->toBeGreaterThan(0)
        ->and($totals['active_technicians'])->toBeGreaterThan(0)
        ->and($totals['overdue_invoices'])->toBeGreaterThan(0)
        ->and($totals['outstanding_balance'])->toBeGreaterThan(0)
        ->and($totals['revenue_this_month'])->toBeGreaterThan(0)
        ->and(CleaningAdminMetrics::latestCleanerLocations()->count())->toBeGreaterThan(0);
});
