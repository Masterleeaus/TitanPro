<?php

namespace Modules\TitanCommand\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanCommand\Models\Work\WorkJob;
use Modules\TitanCommand\Models\Work\WorkJobAssignment;
use Modules\TitanCommand\Models\Work\WorkJobChecklist;
use Modules\TitanCommand\Models\Work\WorkJobChecklistItem;
use Modules\TitanCommand\Models\Work\WorkJobEvidence;
use Modules\TitanCommand\Models\Work\WorkJobInspection;
use Modules\TitanCommand\Models\Work\WorkJobReport;
use Modules\TitanCommand\Models\Work\WorkJobState;
use Tests\TestCase;

/**
 * Feature tests covering the TitanCommand job lifecycle:
 * open → assign → checklist_complete → inspection → closed
 */
class WorkJobLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private int $companyId = 1;
    private int $userId    = 1;

    // -----------------------------------------------------------------------
    // 1. Create
    // -----------------------------------------------------------------------

    /** @test */
    public function a_work_job_can_be_created(): void
    {
        $job = WorkJob::create([
            'company_id' => $this->companyId,
            'user_id'    => $this->userId,
            'title'      => 'Test Job',
            'status'     => 'open',
            'priority'   => 'normal',
        ]);

        $this->assertDatabaseHas('work_jobs', ['id' => $job->id, 'status' => 'open']);
    }

    // -----------------------------------------------------------------------
    // 2. Assign
    // -----------------------------------------------------------------------

    /** @test */
    public function a_work_job_can_be_assigned_to_a_user(): void
    {
        $job = $this->makeJob('open');

        WorkJobAssignment::create([
            'company_id'        => $this->companyId,
            'user_id'           => $this->userId,
            'job_id'            => $job->id,
            'assignee_user_id'  => 42,
            'role'              => 'worker',
            'status'            => 'assigned',
        ]);

        $job->status = 'assigned';
        $job->save();

        $this->assertDatabaseHas('work_jobs', ['id' => $job->id, 'status' => 'assigned']);
        $this->assertDatabaseHas('work_jobs_assignments', ['job_id' => $job->id, 'assignee_user_id' => 42]);
    }

    // -----------------------------------------------------------------------
    // 3. Checklist complete
    // -----------------------------------------------------------------------

    /** @test */
    public function a_work_job_checklist_can_be_completed(): void
    {
        $job = $this->makeJob('assigned');

        $checklist = WorkJobChecklist::create([
            'company_id' => $this->companyId,
            'user_id'    => $this->userId,
            'job_id'     => $job->id,
            'title'      => 'Standard Clean',
            'status'     => 'pending',
        ]);

        WorkJobChecklistItem::create([
            'company_id'   => $this->companyId,
            'user_id'      => $this->userId,
            'checklist_id' => $checklist->id,
            'label'        => 'Kitchen',
            'status'       => 'done',
            'sort_order'   => 10,
        ]);

        $checklist->status = 'complete';
        $checklist->save();

        $job->status = 'checklist_complete';
        $job->save();

        $this->assertDatabaseHas('work_jobs', ['id' => $job->id, 'status' => 'checklist_complete']);
        $this->assertDatabaseHas('work_jobs_checklists', ['job_id' => $job->id, 'status' => 'complete']);
    }

    // -----------------------------------------------------------------------
    // 4. Evidence upload
    // -----------------------------------------------------------------------

    /** @test */
    public function evidence_can_be_attached_to_a_job(): void
    {
        $job = $this->makeJob('in_progress');

        WorkJobEvidence::create([
            'company_id'    => $this->companyId,
            'user_id'       => $this->userId,
            'job_id'        => $job->id,
            'evidence_type' => 'photo',
            'uri'           => 'evidence/job-1/photo.jpg',
            'label'         => 'Pre-clean photo',
        ]);

        $this->assertDatabaseHas('work_jobs_evidence', [
            'job_id'        => $job->id,
            'evidence_type' => 'photo',
        ]);
    }

    // -----------------------------------------------------------------------
    // 5. Inspection
    // -----------------------------------------------------------------------

    /** @test */
    public function a_work_job_can_be_inspected(): void
    {
        $job = $this->makeJob('checklist_complete');

        WorkJobInspection::create([
            'company_id'      => $this->companyId,
            'user_id'         => $this->userId,
            'job_id'          => $job->id,
            'status'          => 'approved',
            'title'           => 'Post-clean inspection',
            'inspection_type' => 'post',
        ]);

        $job->status = 'inspection';
        $job->save();

        $this->assertDatabaseHas('work_jobs_inspections', ['job_id' => $job->id, 'status' => 'approved']);
        $this->assertDatabaseHas('work_jobs', ['id' => $job->id, 'status' => 'inspection']);
    }

    // -----------------------------------------------------------------------
    // 6. Close
    // -----------------------------------------------------------------------

    /** @test */
    public function a_work_job_can_be_closed(): void
    {
        $job = $this->makeJob('inspection');

        WorkJobReport::create([
            'company_id'  => $this->companyId,
            'user_id'     => $this->userId,
            'report_type' => 'summary',
            'label'       => 'Completion Report',
        ]);

        $job->status       = 'closed';
        $job->completed_at = now();
        $job->save();

        $this->assertDatabaseHas('work_jobs', ['id' => $job->id, 'status' => 'closed']);
        $this->assertDatabaseHas('work_jobs_reports', ['report_type' => 'summary', 'company_id' => $this->companyId]);
    }

    // -----------------------------------------------------------------------
    // 7. State machine (work_jobs_states)
    // -----------------------------------------------------------------------

    /** @test */
    public function job_state_machine_records_transitions(): void
    {
        $job = $this->makeJob('open');

        foreach (['open', 'assigned', 'checklist_complete', 'inspection', 'closed'] as $key) {
            WorkJobState::updateOrCreate(
                ['company_id' => $this->companyId, 'user_id' => $this->userId, 'job_id' => $job->id, 'state_key' => $key],
                ['state_type' => 'domain', 'status' => $key === 'closed' ? 'done' : 'ok'],
            );
        }

        $this->assertDatabaseCount('work_jobs_states', 5);
        $this->assertDatabaseHas('work_jobs_states', ['job_id' => $job->id, 'state_key' => 'closed', 'status' => 'done']);
    }

    // -----------------------------------------------------------------------
    // 8. Tenant isolation
    // -----------------------------------------------------------------------

    /** @test */
    public function jobs_from_another_company_are_not_returned_in_tenant_scope(): void
    {
        $otherCompanyId = 999;
        $otherUserId = 999;

        WorkJob::create([
            'company_id' => $otherCompanyId,
            'user_id'    => $otherUserId,
            'title'      => 'Other company job',
            'status'     => 'open',
            'priority'   => 'normal',
        ]);

        $myJob = $this->makeJob('open');

        // Use the scopeTenant() helper which filters by both company_id and user_id
        $results = WorkJob::query()->scopeTenant($this->companyId, $this->userId)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($myJob->id, $results->first()->id);
        // Confirm the other-tenant job is not included
        $this->assertNotEquals($otherCompanyId, $results->first()->company_id);
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function makeJob(string $status): WorkJob
    {
        return WorkJob::create([
            'company_id' => $this->companyId,
            'user_id'    => $this->userId,
            'title'      => "Job ({$status})",
            'status'     => $status,
            'priority'   => 'normal',
        ]);
    }
}
