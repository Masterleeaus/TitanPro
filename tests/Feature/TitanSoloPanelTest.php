<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use App\Services\PlanService;
use Database\Seeders\RolesAndPermissionsSeeder;

test('titansolo panel config is registered with owner-only access metadata', function () {
    $panels = config('titan_panels.panels');

    expect($panels)->toHaveKey('titansolo')
        ->and($panels['titansolo']['path'])->toBe('titansolo')
        ->and($panels['titansolo']['label'])->toBe('TitanSolo')
        ->and($panels['titansolo']['roles'])->toBe(['owner']);
});

test('titansolo dashboard widget includes solo workflow blocks and excludes team features', function () {
    $blade = file_get_contents(resource_path('views/filament/titansolo/widgets/solo-overview-widget.blade.php'));

    expect($blade)->toContain("Today's jobs")
        ->and($blade)->toContain('Outstanding invoices')
        ->and($blade)->toContain('Quick-create job')
        ->and($blade)->not->toContain('dispatch')
        ->and($blade)->not->toContain('team');
});

test('app sidebar includes titansolo product switcher link', function () {
    $sidebar = file_get_contents(resource_path('js/components/AppSidebar.vue'));

    expect($sidebar)->toContain("title: 'TitanSolo Panel'")
        ->and($sidebar)->toContain("href: '/titansolo'");
});

test('solo owner can access titansolo job and invoice pages and complete invoice lifecycle', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $organization = Organization::factory()
        ->onPlan(PlanService::PLAN_STARTER)
        ->create();

    $owner = User::factory()->create([
        'organization_id' => $organization->id,
    ]);
    $owner->assignRole('owner');

    $customer = Customer::factory()->create([
        'organization_id' => $organization->id,
    ]);

    $job = Job::create([
        'organization_id' => $organization->id,
        'customer_id' => $customer->id,
        'title' => 'Solo clean',
        'status' => Job::STATUS_SCHEDULED,
        'scheduled_at' => now(),
    ]);

    $invoice = Invoice::create([
        'organization_id' => $organization->id,
        'customer_id' => $customer->id,
        'job_id' => $job->id,
        'invoice_number' => 'TS-1001',
        'status' => Invoice::STATUS_SENT,
        'total' => 149.00,
        'balance_due' => 149.00,
        'due_at' => now()->addDays(7),
    ]);

    $this->actingAs($owner)
        ->get(route('filament.titansolo.resources.jobs.create'))
        ->assertOk();

    $this->actingAs($owner)
        ->get(route('filament.titansolo.resources.invoices.edit', ['record' => $invoice]))
        ->assertOk();

    $invoice->update([
        'status' => Invoice::STATUS_PAID,
        'amount_paid' => $invoice->total,
        'balance_due' => 0,
        'paid_at' => now(),
    ]);

    expect($invoice->fresh()->status)->toBe(Invoice::STATUS_PAID)
        ->and((float) $invoice->fresh()->balance_due)->toBe(0.0);
});
