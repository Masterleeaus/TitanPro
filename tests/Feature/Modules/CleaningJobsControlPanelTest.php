<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CleaningJobs\ControlPanel\Metrics\DashboardMetrics;
use Modules\CleaningJobs\ControlPanel\Shortcuts\ShortcutRegistry;
use Modules\CleaningJobs\ControlPanel\Tables\TabsRegistry;
use Modules\CleaningJobs\ControlPanel\Widgets\OperationalWidgets;
use Modules\CleaningJobs\Models\WORequest;
use Modules\CleaningJobs\Models\WorkOrder;
use Tests\TestCase;

class CleaningJobsControlPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_metrics_and_widgets_are_tenant_scoped(): void
    {
        [$userA, $orgA, $userB, $orgB] = $this->tenantPair();

        WorkOrder::factory()->create([
            'company_id' => $orgA->id,
            'parent_id' => $orgA->id,
            'scheduled_for' => now()->setTime(9, 0),
            'technician_id' => 11,
            'status' => 'scheduled',
            'title' => 'Lobby clean',
        ]);

        WorkOrder::factory()->create([
            'company_id' => $orgA->id,
            'parent_id' => $orgA->id,
            'scheduled_for' => now()->setTime(12, 0),
            'technician_id' => 22,
            'status' => 'in_progress',
            'title' => 'Office clean',
        ]);

        WorkOrder::factory()->create([
            'company_id' => $orgA->id,
            'parent_id' => $orgA->id,
            'scheduled_for' => now()->subDay(),
            'due_by' => now()->subDay(),
            'status' => 'open',
            'technician_id' => 33,
            'title' => 'Overdue clean',
        ]);

        WorkOrder::factory()->create([
            'company_id' => $orgB->id,
            'parent_id' => $orgB->id,
            'scheduled_for' => now()->setTime(15, 0),
            'technician_id' => 44,
            'status' => 'scheduled',
            'title' => 'Other tenant clean',
        ]);

        WORequest::query()->create([
            'request_detail' => 'Restock lobby supplies',
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => today()->addDay(),
            'assign' => 11,
            'parent_id' => $orgA->id,
        ]);

        WORequest::query()->create([
            'request_detail' => 'Approve weekend clean',
            'priority' => 'medium',
            'status' => 'in_progress',
            'due_date' => today()->addDays(2),
            'assign' => 22,
            'parent_id' => $orgA->id,
        ]);

        WORequest::query()->create([
            'request_detail' => 'Closed request',
            'priority' => 'low',
            'status' => 'completed',
            'due_date' => today(),
            'assign' => 22,
            'parent_id' => $orgA->id,
        ]);

        WORequest::query()->create([
            'request_detail' => 'Other tenant request',
            'priority' => 'critical',
            'status' => 'pending',
            'due_date' => today()->addDay(),
            'assign' => 44,
            'parent_id' => $orgB->id,
        ]);

        $this->actingAs($userA);

        $metrics = app(DashboardMetrics::class);
        $widgets = app(OperationalWidgets::class);

        $this->assertSame(2, $metrics->jobsToday());
        $this->assertSame(1, $metrics->overdueJobs());
        $this->assertSame(2, $metrics->activeCleaners());

        $todaysJobs = $widgets->todaysJobs();
        $pendingRequests = $widgets->pendingRequests();

        $this->assertCount(2, $todaysJobs);
        $this->assertCount(2, $pendingRequests);
        $this->assertContains('Lobby clean', $todaysJobs->pluck('title')->all());
        $this->assertNotContains('Other tenant clean', $todaysJobs->pluck('title')->all());
        $this->assertContains('Restock lobby supplies', $pendingRequests->pluck('request_detail')->all());
        $this->assertNotContains('Other tenant request', $pendingRequests->pluck('request_detail')->all());
    }

    public function test_shortcuts_and_tabs_reference_existing_classes(): void
    {
        foreach (ShortcutRegistry::getShortcuts() as $shortcut) {
            $this->assertArrayHasKey('action', $shortcut);
            $this->assertTrue(class_exists($shortcut['action']));
        }

        foreach (TabsRegistry::getTabs() as $tab) {
            $this->assertArrayHasKey('provider', $tab);
            $this->assertTrue(class_exists($tab['provider']));
        }
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
