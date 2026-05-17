<?php

namespace Modules\TitanCommand\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * TitanBOS seed data — default job lifecycle states.
 * Seeds platform-level (company_id=0) reference data only.
 * Per-tenant states are created at runtime from these defaults.
 */
class WorkJobStateSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $defaultStates = [
            ['state_key' => 'open',               'label' => 'Open',               'state_type' => 'domain', 'status' => 'ok'],
            ['state_key' => 'assigned',           'label' => 'Assigned',           'state_type' => 'domain', 'status' => 'ok'],
            ['state_key' => 'in_progress',        'label' => 'In Progress',        'state_type' => 'domain', 'status' => 'ok'],
            ['state_key' => 'checklist_complete', 'label' => 'Checklist Complete', 'state_type' => 'domain', 'status' => 'ok'],
            ['state_key' => 'inspection',         'label' => 'Inspection',         'state_type' => 'qa',     'status' => 'pending'],
            ['state_key' => 'closed',             'label' => 'Closed',             'state_type' => 'domain', 'status' => 'done'],
            ['state_key' => 'cancelled',          'label' => 'Cancelled',          'state_type' => 'domain', 'status' => 'fail'],
        ];

        // Reference states are stored at company_id=0, user_id=0, job_id=0
        foreach ($defaultStates as $state) {
            DB::table('work_jobs_states')->updateOrInsert(
                ['company_id' => 0, 'user_id' => 0, 'job_id' => 0, 'state_key' => $state['state_key']],
                array_merge($state, [
                    'company_id' => 0,
                    'user_id'    => 0,
                    'job_id'     => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
