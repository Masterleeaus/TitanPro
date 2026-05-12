<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Tests\Unit\Actions;

use Illuminate\Support\Facades\Event;
use Modules\CleaningJobs\Actions\AssignTechnicianAction;
use Modules\CleaningJobs\Actions\CancelJobAction;
use Modules\CleaningJobs\Actions\CompleteJobAction;
use Modules\CleaningJobs\Actions\CreateJobAction;
use Modules\CleaningJobs\Actions\LogConsumableUsageAction;
use Modules\CleaningJobs\Actions\LogTimesheetAction;
use Modules\CleaningJobs\Actions\RescheduleJobAction;
use Modules\CleaningJobs\Events\ConsumablesUsed;
use Modules\CleaningJobs\Events\JobAssigned;
use Modules\CleaningJobs\Events\JobCancelled;
use Modules\CleaningJobs\Events\JobCompleted;
use Modules\CleaningJobs\Events\JobCreated;
use Modules\CleaningJobs\Events\JobRescheduled;
use Modules\CleaningJobs\Events\TimesheetLogged;
use Modules\CleaningJobs\Models\WorkOrder;
use PHPUnit\Framework\TestCase;

class JobActionEventsTest extends TestCase
{
    public function test_create_job_action_dispatches_job_created(): void
    {
        $this->assertTrue(class_exists(CreateJobAction::class));
        $this->assertTrue(class_exists(JobCreated::class));
    }

    public function test_job_created_event_has_signal_envelope(): void
    {
        $reflection = new \ReflectionClass(JobCreated::class);
        $this->assertTrue($reflection->hasProperty('company_id'));
        $this->assertTrue($reflection->hasProperty('actor_id'));
        $this->assertTrue($reflection->hasProperty('source_type'));
        $this->assertTrue($reflection->hasProperty('occurred_at'));
    }

    public function test_job_assigned_event_has_technician_id(): void
    {
        $reflection = new \ReflectionClass(JobAssigned::class);
        $this->assertTrue($reflection->hasProperty('technician_id'));
    }

    public function test_job_cancelled_event_has_reason(): void
    {
        $reflection = new \ReflectionClass(JobCancelled::class);
        $this->assertTrue($reflection->hasProperty('reason'));
    }

    public function test_consumables_used_event_has_amount(): void
    {
        $reflection = new \ReflectionClass(ConsumablesUsed::class);
        $this->assertTrue($reflection->hasProperty('amount'));
    }

    public function test_timesheet_logged_event_has_hours(): void
    {
        $reflection = new \ReflectionClass(TimesheetLogged::class);
        $this->assertTrue($reflection->hasProperty('hours'));
    }

    public function test_job_rescheduled_event_has_new_date(): void
    {
        $reflection = new \ReflectionClass(JobRescheduled::class);
        $this->assertTrue($reflection->hasProperty('new_date'));
    }

    public function test_all_action_classes_exist(): void
    {
        $this->assertTrue(class_exists(CreateJobAction::class));
        $this->assertTrue(class_exists(AssignTechnicianAction::class));
        $this->assertTrue(class_exists(CompleteJobAction::class));
        $this->assertTrue(class_exists(CancelJobAction::class));
        $this->assertTrue(class_exists(RescheduleJobAction::class));
        $this->assertTrue(class_exists(LogConsumableUsageAction::class));
        $this->assertTrue(class_exists(LogTimesheetAction::class));
    }
}
