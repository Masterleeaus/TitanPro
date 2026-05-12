<?php

namespace Modules\CRMCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\CRMCore\Models\CRMCoreActivityLog;
use Modules\CRMCore\Models\Company;
use Modules\CRMCore\Models\Contact;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealPipeline;
use Modules\CRMCore\Models\DealStage;
use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Models\LeadSource;
use Modules\CRMCore\Models\LeadStatus;
use Modules\CRMCore\Models\Task;
use Modules\CRMCore\Models\TaskPriority;
use Modules\CRMCore\Models\TaskStatus;

class CRMCoreTestFixturesSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::withoutGlobalScopes()->firstOrCreate(
            ['name' => 'Fixture Company'],
            ['is_active' => true],
        );

        $contact = Contact::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id, 'email_primary' => 'fixture.crm@example.test'],
            ['first_name' => 'Fixture', 'last_name' => 'Contact'],
        );

        $pipeline = DealPipeline::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id, 'name' => 'Fixture Pipeline'],
            ['is_default' => true, 'position' => 1, 'is_active' => true],
        );

        $stage = DealStage::withoutGlobalScopes()->firstOrCreate(
            ['pipeline_id' => $pipeline->id, 'name' => 'Qualified'],
            ['position' => 1, 'is_default_for_pipeline' => true, 'color' => '#3b82f6'],
        );

        $leadStatus = LeadStatus::withoutGlobalScopes()->firstOrCreate(
            ['name' => 'New'],
            ['position' => 1, 'is_default' => true, 'is_final' => false],
        );

        $leadSource = LeadSource::withoutGlobalScopes()->firstOrCreate(['name' => 'Fixture Source']);

        Lead::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id, 'contact_email' => $contact->email_primary],
            [
                'title' => 'Fixture Lead',
                'lead_status_id' => $leadStatus->id,
                'lead_source_id' => $leadSource->id,
            ],
        );

        Deal::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id, 'title' => 'Fixture Deal'],
            ['pipeline_id' => $pipeline->id, 'deal_stage_id' => $stage->id, 'contact_id' => $contact->id, 'value' => 1000],
        );

        $taskStatus = TaskStatus::withoutGlobalScopes()->firstOrCreate(
            ['name' => 'Open'],
            ['position' => 1, 'is_default' => true, 'is_completed_status' => false],
        );

        $taskPriority = TaskPriority::withoutGlobalScopes()->firstOrCreate(
            ['name' => 'Normal'],
            ['level' => 1, 'is_default' => true],
        );

        Task::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id, 'title' => 'Fixture Task'],
            ['task_status_id' => $taskStatus->id, 'task_priority_id' => $taskPriority->id],
        );

        CRMCoreActivityLog::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id, 'event' => 'fixture.seeded'],
            ['subject_type' => Company::class, 'subject_id' => $company->id, 'payload' => ['seed' => true]],
        );

        DB::table('crm_notes')->updateOrInsert(
            ['company_id' => $company->id, 'noteable_type' => Company::class, 'noteable_id' => $company->id],
            ['content' => 'Fixture note', 'updated_at' => now(), 'created_at' => now()],
        );

        $tagId = DB::table('crm_tags')->where('company_id', $company->id)->where('name', 'fixture')->value('id');
        if ($tagId === null) {
            $tagId = DB::table('crm_tags')->insertGetId([
                'company_id' => $company->id,
                'name' => 'fixture',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('crm_taggables')->updateOrInsert(
            ['company_id' => $company->id, 'crm_tag_id' => $tagId, 'taggable_type' => Contact::class, 'taggable_id' => $contact->id],
            ['created_at' => now(), 'updated_at' => now()],
        );
    }
}
