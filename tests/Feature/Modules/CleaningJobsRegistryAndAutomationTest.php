<?php

use App\Platform\Automation\AutomationRegistry;
use App\Platform\Modules\DashboardRegistry;
use App\Platform\Modules\SettingsRegistry;
use App\Platform\Modules\ShortcutRegistry;
use App\Platform\Modules\TableRegistry;
use App\Platform\Tenancy\TenancyRegistry;
use App\Platform\Workflows\WorkflowDefinitionRegistry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;
use Modules\CleaningJobs\Automation\Handlers\SendAppointmentReminderHandler;
use Modules\CleaningJobs\Jobs\DispatchCleaningReminder;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;
use Modules\CleaningJobs\Workflows\Definitions\CleaningJobWorkflow;
use Tests\TestCase;

class CleaningJobsRegistryAndAutomationTest extends TestCase
{
    public function test_platform_registries_include_cleaningjobs_entries(): void
    {
        $this->assertNotNull(app(DashboardRegistry::class)->manifest('CleaningJobs'));
        $this->assertNotNull(app(TableRegistry::class)->find('CleaningJobs', 'jobs'));
        $this->assertNotNull(app(ShortcutRegistry::class)->find('CleaningJobs', 'create_booking'));
        $this->assertNotNull(app(SettingsRegistry::class)->find('CleaningJobs', 'auto_convert_on_complete'));

        $tenantResolver = app(TenancyRegistry::class)->findResolver('cleaningjobs.company');
        $workflow = app(WorkflowDefinitionRegistry::class)->find('cleaningjobs.work_order');

        $this->assertSame(CompanyTenantResolver::class, $tenantResolver['class'] ?? null);
        $this->assertSame(CleaningJobWorkflow::class, $workflow['class'] ?? null);
    }

    public function test_automation_registry_and_queue_hook_are_wired(): void
    {
        $registry = app(AutomationRegistry::class);

        $scheduledReminder = $registry->find('cleaningjobs.job_scheduled_reminder');
        $dailyScheduler = $registry->find('cleaningjobs.daily_job_reminders');
        $manifestEntries = $registry->manifestEntries()['CleaningJobs'] ?? [];

        $this->assertSame(SendAppointmentReminderHandler::class, $scheduledReminder['handler'] ?? null);
        $this->assertSame('daily', $dailyScheduler['schedule'] ?? null);
        $this->assertCount(3, $manifestEntries['triggers'] ?? []);
        $this->assertCount(1, $manifestEntries['handlers'] ?? []);
        $this->assertCount(1, $manifestEntries['pipelines'] ?? []);
        $this->assertCount(1, $manifestEntries['schedulers'] ?? []);
        $this->assertInstanceOf(ShouldQueue::class, new DispatchCleaningReminder(123));

        Queue::fake();

        $order = new WorkOrder();
        $order->id = 321;

        (new SendAppointmentReminderHandler())->handle($order);

        Queue::assertPushed(DispatchCleaningReminder::class, function (DispatchCleaningReminder $job): bool {
            return $job->workOrderId === 321;
        });
    }
}
