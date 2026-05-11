<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seed default templates for the current tenant at runtime is not possible here.
        // Instead, seed "global" templates with company_id/user_id = 0 as defaults.
        // Controllers will clone on first use per tenant.
        $now = now();

        $exists = DB::table('work_jobs_templates')
            ->where('company_id', 0)->where('user_id', 0)
            ->where('template_type', 'checklist')
            ->where('title', 'Standard Clean Checklist')
            ->exists();

        if (!$exists) {
            $templateId = DB::table('work_jobs_templates')->insertGetId([
                'company_id' => 0,
                'user_id' => 0,
                'template_type' => 'checklist',
                'title' => 'Standard Clean Checklist',
                'status' => 'active',
                'meta_json' => json_encode(['source' => 'titancommand-pass5']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $items = [
                ['label' => 'Arrive & check access notes', 'sort_order' => 10],
                ['label' => 'Pre-clean photos (required)', 'sort_order' => 20, 'schema_json' => ['requires_evidence' => ['photo']]],
                ['label' => 'Kitchen', 'sort_order' => 30],
                ['label' => 'Bathrooms', 'sort_order' => 40],
                ['label' => 'Floors', 'sort_order' => 50],
                ['label' => 'Bins & final wipe', 'sort_order' => 60],
                ['label' => 'Post-clean photos (required)', 'sort_order' => 70, 'schema_json' => ['requires_evidence' => ['photo']]],
                ['label' => 'Client sign-off (if present)', 'sort_order' => 80, 'schema_json' => ['requires_evidence' => ['signature']]],
            ];

            foreach ($items as $it) {
                DB::table('work_jobs_template_items')->insert([
                    'company_id' => 0,
                    'user_id' => 0,
                    'template_id' => $templateId,
                    'item_type' => 'checklist_item',
                    'label' => $it['label'],
                    'status' => 'active',
                    'sort_order' => $it['sort_order'],
                    'schema_json' => isset($it['schema_json']) ? json_encode($it['schema_json']) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $exists2 = DB::table('work_jobs_templates')
            ->where('company_id', 0)->where('user_id', 0)
            ->where('template_type', 'inspection')
            ->where('title', 'Standard Inspection')
            ->exists();

        if (!$exists2) {
            $templateId = DB::table('work_jobs_templates')->insertGetId([
                'company_id' => 0,
                'user_id' => 0,
                'template_type' => 'inspection',
                'title' => 'Standard Inspection',
                'status' => 'active',
                'meta_json' => json_encode(['source' => 'titancommand-pass5']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $items = [
                ['label' => 'Kitchen standards met', 'sort_order' => 10],
                ['label' => 'Bathrooms standards met', 'sort_order' => 20],
                ['label' => 'Floors standards met', 'sort_order' => 30],
                ['label' => 'No missed areas', 'sort_order' => 40],
                ['label' => 'Photos attached', 'sort_order' => 50, 'schema_json' => ['requires_evidence' => ['photo']]],
            ];

            foreach ($items as $it) {
                DB::table('work_jobs_template_items')->insert([
                    'company_id' => 0,
                    'user_id' => 0,
                    'template_id' => $templateId,
                    'item_type' => 'inspection_item',
                    'label' => $it['label'],
                    'status' => 'active',
                    'sort_order' => $it['sort_order'],
                    'schema_json' => isset($it['schema_json']) ? json_encode($it['schema_json']) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Evidence rule template (defaults)
        $exists3 = DB::table('work_jobs_templates')
            ->where('company_id', 0)->where('user_id', 0)
            ->where('template_type', 'evidence_rule')
            ->where('title', 'Default Evidence Rules')
            ->exists();

        if (!$exists3) {
            $templateId = DB::table('work_jobs_templates')->insertGetId([
                'company_id' => 0,
                'user_id' => 0,
                'template_type' => 'evidence_rule',
                'title' => 'Default Evidence Rules',
                'status' => 'active',
                'meta_json' => json_encode(['source' => 'titancommand-pass5']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $reqs = [
                ['label' => 'closed_requires_photos', 'sort_order' => 10, 'schema_json' => ['when_job_status' => 'closed', 'requires' => ['photo']]],
                ['label' => 'signoff_requires_signature', 'sort_order' => 20, 'schema_json' => ['when' => 'signoff', 'requires' => ['signature']]],
            ];

            foreach ($reqs as $r) {
                DB::table('work_jobs_template_items')->insert([
                    'company_id' => 0,
                    'user_id' => 0,
                    'template_id' => $templateId,
                    'item_type' => 'evidence_requirement',
                    'label' => $r['label'],
                    'status' => 'active',
                    'sort_order' => $r['sort_order'],
                    'schema_json' => json_encode($r['schema_json']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // no-op: keep default templates
    }
};
