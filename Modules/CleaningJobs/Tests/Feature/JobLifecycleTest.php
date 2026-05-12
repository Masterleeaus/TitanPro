<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\CleaningJobs\Actions\AssignTechnicianAction;
use Modules\CleaningJobs\Actions\CancelJobAction;
use Modules\CleaningJobs\Actions\CompleteJobAction;
use Modules\CleaningJobs\Actions\CreateJobAction;
use Modules\CleaningJobs\Actions\HoldJobAction;
use Modules\CleaningJobs\Actions\StartJobAction;
use Modules\CleaningJobs\Events\JobAssigned;
use Modules\CleaningJobs\Events\JobCancelled;
use Modules\CleaningJobs\Events\JobCompleted;
use Modules\CleaningJobs\Events\JobCreated;
use Modules\CleaningJobs\Events\JobOnHold;
use Modules\CleaningJobs\Events\JobStarted;
use Tests\TestCase;

class JobLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_lifecycle_create_assign_start_complete(): void
    {
        Event::fake();

        $job = (new CreateJobAction())->handle([
            'title'      => 'Test Cleaning Job',
            'status'     => 'pending',
            'company_id' => 1,
        ]);

        Event::assertDispatched(JobCreated::class);

        (new AssignTechnicianAction())->handle($job, 5);
        Event::assertDispatched(JobAssigned::class, fn ($e) => $e->technician_id === '5');

        (new StartJobAction())->handle($job);
        Event::assertDispatched(JobStarted::class);

        (new CompleteJobAction())->handle($job);
        Event::assertDispatched(JobCompleted::class);

        $this->assertEquals('completed', $job->fresh()->status);
    }

    public function test_job_can_be_cancelled(): void
    {
        Event::fake();

        $job = (new CreateJobAction())->handle([
            'title'      => 'Cancellable Job',
            'status'     => 'pending',
            'company_id' => 1,
        ]);

        (new CancelJobAction())->handle($job, 'Client requested cancellation');
        Event::assertDispatched(JobCancelled::class, fn ($e) => $e->reason === 'Client requested cancellation');
        $this->assertEquals('cancelled', $job->fresh()->status);
    }

    public function test_job_can_be_put_on_hold(): void
    {
        Event::fake();

        $job = (new CreateJobAction())->handle([
            'title'      => 'Hold Job',
            'status'     => 'pending',
            'company_id' => 1,
        ]);

        (new HoldJobAction())->handle($job);
        Event::assertDispatched(JobOnHold::class);
        $this->assertEquals('on_hold', $job->fresh()->status);
    }
}
