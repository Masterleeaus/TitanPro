<?php

use App\Filament\TitanPro\Widgets\ActiveOrganizationsWidget;
use App\Filament\TitanPro\Widgets\FailedJobsWidget;
use App\Filament\TitanPro\Widgets\PlatformRevenueWidget;
use App\Filament\TitanPro\Widgets\UsageMetricsWidget;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PlanService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

test('super admin can render titanpro dashboard widgets', function () {
    (new RolesAndPermissionsSeeder)->run();

    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get('/titanpro')
        ->assertOk()
        ->assertSee('Active Organizations')
        ->assertSee('Platform Revenue')
        ->assertSee('Usage Metrics')
        ->assertSee('Failed Jobs');
});

test('titanpro reporting widgets return expected cross-tenant aggregates', function () {
    Cache::flush();
    Carbon::setTestNow('2026-05-15 12:00:00');

    $orgA = Organization::factory()->withoutSubscription()->create(['plan' => PlanService::PLAN_GROWTH]);
    $orgB = Organization::factory()->withoutSubscription()->create(['plan' => PlanService::PLAN_PRO]);
    $orgC = Organization::factory()->withoutSubscription()->create(['plan' => PlanService::PLAN_STARTER]);

    Subscription::create([
        'organization_id' => $orgA->id,
        'plan' => PlanService::PLAN_GROWTH,
        'status' => Subscription::STATUS_ACTIVE,
        'billing_interval' => 'monthly',
        'created_at' => now()->subMonths(2),
        'updated_at' => now()->subMonths(2),
    ]);
    Subscription::create([
        'organization_id' => $orgB->id,
        'plan' => PlanService::PLAN_PRO,
        'status' => Subscription::STATUS_CANCELED,
        'billing_interval' => 'annual',
        'created_at' => now()->subMonths(2),
        'updated_at' => now()->subMonths(2),
    ]);
    Subscription::create([
        'organization_id' => $orgC->id,
        'plan' => PlanService::PLAN_STARTER,
        'status' => Subscription::STATUS_ACTIVE,
        'billing_interval' => 'monthly',
        'created_at' => now()->subMonths(2),
        'updated_at' => now()->subMonths(2),
    ]);

    Subscription::create([
        'organization_id' => $orgA->id,
        'plan' => PlanService::PLAN_GROWTH,
        'status' => Subscription::STATUS_ACTIVE,
        'billing_interval' => 'monthly',
        'created_at' => now()->subDays(5),
        'updated_at' => now()->subDays(5),
    ]);
    Subscription::create([
        'organization_id' => $orgB->id,
        'plan' => PlanService::PLAN_PRO,
        'status' => Subscription::STATUS_ACTIVE,
        'billing_interval' => 'annual',
        'created_at' => now()->subDays(5),
        'updated_at' => now()->subDays(5),
    ]);
    Subscription::create([
        'organization_id' => $orgC->id,
        'plan' => PlanService::PLAN_STARTER,
        'status' => Subscription::STATUS_CANCELED,
        'billing_interval' => 'monthly',
        'created_at' => now()->subDays(5),
        'updated_at' => now()->subDays(5),
    ]);

    $customerA = Customer::factory()->create(['organization_id' => $orgA->id]);
    $customerB = Customer::factory()->create(['organization_id' => $orgB->id]);

    Job::factory()->forCustomer($customerA)->create(['organization_id' => $orgA->id]);
    Job::factory()->forCustomer($customerA)->create(['organization_id' => $orgA->id]);
    Job::factory()->forCustomer($customerB)->create(['organization_id' => $orgB->id]);

    $invoiceA = Invoice::factory()->forCustomer($customerA)->create([
        'organization_id' => $orgA->id,
        'status' => Invoice::STATUS_PAID,
        'total' => 300,
        'amount_paid' => 300,
        'balance_due' => 0,
    ]);
    $invoiceB = Invoice::factory()->forCustomer($customerB)->create([
        'organization_id' => $orgB->id,
        'status' => Invoice::STATUS_PAID,
        'total' => 200,
        'amount_paid' => 200,
        'balance_due' => 0,
    ]);

    Payment::factory()->forInvoice($invoiceA)->create([
        'organization_id' => $orgA->id,
        'amount' => 300,
        'paid_at' => now()->startOfMonth()->addDays(5),
    ]);
    Payment::factory()->forInvoice($invoiceB)->create([
        'organization_id' => $orgB->id,
        'amount' => 200,
        'paid_at' => now()->startOfMonth()->addDays(6),
    ]);
    Payment::factory()->forInvoice($invoiceA)->create([
        'organization_id' => $orgA->id,
        'amount' => 100,
        'paid_at' => now()->subMonthNoOverflow()->startOfMonth()->addDays(3),
    ]);

    DB::table('failed_jobs')->insert([
        [
            'uuid' => (string) Str::uuid(),
            'connection' => 'database',
            'queue' => 'default',
            'payload' => '{}',
            'exception' => 'Exception 1',
            'failed_at' => now()->subMinutes(10),
        ],
        [
            'uuid' => (string) Str::uuid(),
            'connection' => 'database',
            'queue' => 'default',
            'payload' => '{}',
            'exception' => 'Exception 2',
            'failed_at' => now()->subMinutes(2),
        ],
    ]);

    $activeMetrics = ActiveOrganizationsWidget::metrics();
    $revenueMetrics = PlatformRevenueWidget::metrics();
    $usageMetrics = UsageMetricsWidget::metrics();
    $failedMetrics = FailedJobsWidget::metrics();

    expect($activeMetrics['current'])->toBe(2)
        ->and($activeMetrics['previous'])->toBe(2)
        ->and($activeMetrics['delta'])->toBe(0)
        ->and($revenueMetrics['mrr_current'])->toBe(348.0)
        ->and($revenueMetrics['mrr_previous'])->toBe(228.0)
        ->and($revenueMetrics['revenue_current'])->toBe(500.0)
        ->and($revenueMetrics['revenue_previous'])->toBe(100.0)
        ->and($usageMetrics['jobs'])->toBe(3)
        ->and($usageMetrics['invoices'])->toBe(2)
        ->and($usageMetrics['payments'])->toBe(3)
        ->and($failedMetrics['count'])->toBe(2)
        ->and($failedMetrics['last_failure_at'])->not->toBeNull();

    Carbon::setTestNow();
});
