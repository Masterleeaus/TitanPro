<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Policies\WorkOrderPolicy;
use Modules\CleaningJobs\Services\JobLifecycleService;
use Tests\TestCase;

class CleaningJobsTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_work_orders_are_scoped_to_the_current_tenant(): void
    {
        [$userA, $orgA, $userB, $orgB] = $this->tenantPair();

        $ownOrder = WorkOrder::factory()->create([
            'company_id' => $orgA->id,
            'parent_id' => $orgA->id,
        ]);

        $legacyOrder = WorkOrder::factory()->create([
            'company_id' => null,
            'parent_id' => $orgA->id,
        ]);

        WorkOrder::factory()->create([
            'company_id' => $orgB->id,
            'parent_id' => $orgB->id,
        ]);

        $this->actingAs($userA);

        $visibleIds = WorkOrder::query()->pluck('id')->all();

        $this->assertSameCanonicalizing([$ownOrder->id, $legacyOrder->id], $visibleIds);
    }

    public function test_job_lifecycle_service_stamps_current_tenant_ids(): void
    {
        [$userA, $orgA] = $this->tenantPair();

        $this->actingAs($userA);

        $order = app(JobLifecycleService::class)->create([
            'client_id' => 1,
            'title' => 'Tenant stamped job',
        ]);

        $this->assertSame($orgA->id, $order->company_id);
        $this->assertSame($orgA->id, $order->parent_id);
    }

    public function test_work_order_policy_blocks_cross_tenant_records_and_allows_legacy_fallback(): void
    {
        [$userA, $orgA, $userB, $orgB] = $this->tenantPair();

        $policy = app(WorkOrderPolicy::class);

        $sameTenant = new WorkOrder(['company_id' => $orgA->id]);
        $legacyTenant = new WorkOrder(['parent_id' => $orgA->id]);
        $otherTenant = new WorkOrder(['company_id' => $orgB->id, 'parent_id' => $orgB->id]);

        $this->assertTrue($policy->view($userA, $sameTenant));
        $this->assertTrue($policy->update($userA, $legacyTenant));
        $this->assertFalse($policy->delete($userA, $otherTenant));
        $this->assertFalse($policy->view($userB, $sameTenant));
    }

    private function tenantPair(): array
    {
        $orgA = Organization::factory()->create();
        $userA = User::factory()->create(['organization_id' => $orgA->id]);

        $orgB = Organization::factory()->create();
        $userB = User::factory()->create(['organization_id' => $orgB->id]);

        return [$userA, $orgA, $userB, $orgB];
    }
}
