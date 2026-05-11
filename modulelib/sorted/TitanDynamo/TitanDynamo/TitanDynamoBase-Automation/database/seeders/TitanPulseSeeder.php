<?php

namespace App\Extensions\TitanPulse\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitanPulseSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('teams')) {
            return;
        }

        $teams = DB::table('teams')->select('id')->limit(5000)->get();

        foreach ($teams as $t) {
            $teamId = (int)$t->id;

            $this->seedRulesForTeam($teamId);
        }
    }

    private function seedRulesForTeam(int $teamId): void
    {
        $rules = $this->defaultCleanerRules($teamId);

        foreach ($rules as $r) {
            $exists = DB::table('tz_automation_rules')
                ->where('team_id', $teamId)
                ->where('trigger_type', $r['trigger_type'])
                ->where('trigger_event', $r['trigger_event'])
                ->where('actions_json', $r['actions_json'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('tz_automation_rules')->insert(array_merge($r, [
                'team_id' => $teamId,
                'company_id' => $teamId,
                'user_id' => null,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function defaultCleanerRules(int $teamId): array
    {
        return [
            // Rule 1 — Morning “Today Plan”
            [
                'trigger_type' => 'schedule',
                'trigger_event' => 'schedule.daily.05:30',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'morning_plan',
                        'severity' => 0,
                        'title' => 'Build Today Plan',
                        'body' => 'Build Today Plan: list today’s jobs in order, highlight access notes, estimate total duration, flag any missing checklist/evidence requirements.',
                        'subject_type' => 'team',
                        'subject_id' => $teamId,
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 2 — Job Scheduled → Pre-clean Checklist Prompt
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.scheduled',
                'conditions_json' => json_encode([
                    ['field' => 'payload.checklist_required', 'op' => '==', 'value' => true],
                    ['field' => 'payload.tasks_count', 'op' => '==', 'value' => 0],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'checklist',
                        'severity' => 1,
                        'title' => 'Checklist template missing',
                        'body' => 'Job scheduled with checklist_required=true but tasks_count=0. Add checklist template for this service package.',
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'apply_checklist_template',
                        'title' => 'Apply default checklist template (service package)',
                        'body' => 'Queued for human approval: apply default checklist template for this job/service package.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 3 — 24h Before Job → Access Confirmation Reminder
            [
                'trigger_type' => 'schedule',
                'trigger_event' => 'schedule.hourly',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'access',
                        'severity' => 1,
                        'title' => 'Access info missing for upcoming job',
                        'body' => 'Access info missing for upcoming job. Add entry/lockbox/alarm notes.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 4 — Job Start Window Passed → “Late Start” Alert
            [
                'trigger_type' => 'schedule',
                'trigger_event' => 'schedule.every_30_min',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'late_start',
                        'severity' => 2,
                        'title' => 'Job is late to start',
                        'body' => 'Job is late to start. Options: start now, reschedule, mark blocked with reason.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 5 — Job Started → Evidence Reminder (if required)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.started',
                'conditions_json' => json_encode([
                    ['field' => 'payload.evidence_required', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'evidence',
                        'severity' => 1,
                        'title' => 'Evidence required',
                        'body' => 'Evidence required. Capture before/after photos + any problem areas.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 6 — Job Completed but Evidence Missing → Block Completion Path (job.completed)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.evidence_required', 'op' => '==', 'value' => true],
                    ['field' => 'payload.evidence_count', 'op' => '==', 'value' => 0],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'evidence_blocker',
                        'severity' => 2,
                        'title' => 'Completion blocked: missing evidence',
                        'body' => 'Completion blocked: evidence required but missing. Upload photos/PDF.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],
            // Rule 6b — attempt_complete
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.attempt_complete',
                'conditions_json' => json_encode([
                    ['field' => 'payload.evidence_required', 'op' => '==', 'value' => true],
                    ['field' => 'payload.evidence_count', 'op' => '==', 'value' => 0],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'evidence_blocker',
                        'severity' => 2,
                        'title' => 'Completion blocked: missing evidence',
                        'body' => 'Completion blocked: evidence required but missing. Upload photos/PDF.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 7 — Job Failed/Blocked → “Next Action” Pack (failed)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.failed',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'recovery',
                        'severity' => 2,
                        'title' => 'Job failed: next action required',
                        'body' => 'Log failure reason + what to do next (reschedule / client call / access issue).',
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'create_next_visit_note',
                        'title' => "Create 'Next Visit Note' from failure reason",
                        'body' => "Queued for human approval: create a Next Visit Note from the failure reason to guide the next attempt.",
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],
            // Rule 7b — blocked
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.blocked',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'recovery',
                        'severity' => 2,
                        'title' => 'Job blocked: next action required',
                        'body' => 'Log block reason + what to do next (reschedule / client call / access issue).',
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'create_next_visit_note',
                        'title' => "Create 'Next Visit Note' from block reason",
                        'body' => "Queued for human approval: create a Next Visit Note from the block reason to guide the next attempt.",
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 8 — Job Completed → Invoice Draft Suggestion (invoice_required=true)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.invoice_required', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'invoice',
                        'severity' => 1,
                        'title' => 'Draft invoice now',
                        'body' => 'Job completed. Draft invoice now (job ref, service package, extras).',
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'create_invoice_draft',
                        'title' => 'Create invoice draft record',
                        'body' => 'Queued for human approval: create an invoice draft record (no sending, no payments).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],
            // Rule 8b — job_type one_off/ad_hoc
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.job_type', 'op' => 'in', 'value' => ['one_off', 'ad_hoc']],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'invoice',
                        'severity' => 1,
                        'title' => 'Draft invoice now',
                        'body' => 'Job completed (one-off/ad-hoc). Draft invoice now (job ref, service package, extras).',
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'create_invoice_draft',
                        'title' => 'Create invoice draft record',
                        'body' => 'Queued for human approval: create an invoice draft record (no sending, no payments).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 9 — Recurring Series Drift Detector
            [
                'trigger_type' => 'schedule',
                'trigger_event' => 'schedule.daily.02:00',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'scheduling_hygiene',
                        'severity' => 1,
                        'title' => 'Recurring schedule drift detected',
                        'body' => 'Recurring schedule drift detected. Repair series (rebuild next 4 occurrences).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 10 — Overdue Queue Builder
            [
                'trigger_type' => 'schedule',
                'trigger_event' => 'schedule.daily.06:00',
                'conditions_json' => json_encode([
                    ['field' => 'payload.overdue_count', 'op' => '>', 'value' => 0],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'morning_pack',
                        'severity' => 2,
                        'title' => 'Overdue jobs detected',
                        'body' => 'Overdue jobs detected: prioritize reschedule/cancel/mark failed.',
                        'subject_type' => 'team',
                        'subject_id' => $teamId,
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'bulk_reschedule_proposal',
                        'title' => 'Bulk reschedule proposal (top 5 first)',
                        'body' => 'Queued for human approval: create a bulk reschedule proposal for overdue jobs (start with top 5).',
                        'subject_type' => 'team',
                        'subject_id' => $teamId,
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 11 — “Next Visit Note” (recurring)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.recurring', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'next_visit_note',
                        'severity' => 1,
                        'title' => 'Add Next Visit Note',
                        'body' => 'Add Next Visit Note: what should be done differently next time?',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],
            // Rule 11b — recurring nested payload.job.recurring
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.job.recurring', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'next_visit_note',
                        'severity' => 1,
                        'title' => 'Add Next Visit Note',
                        'body' => 'Add Next Visit Note: what should be done differently next time?',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 12a — Evidence Quality Check (analysis)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.evidence_required', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'run_analysis', 'params' => [
                        'analysis_type' => 'evidence_quality_check',
                        'title' => 'Evidence Quality Check',
                        'summary' => 'Checks evidence_count, image quality hints, and before/after pair completeness (heuristic).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],
            // Rule 12b — Evidence Quality low → Suggest more photos
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'evidence_quality_low', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'evidence_quality',
                        'severity' => 2,
                        'title' => 'Evidence quality low',
                        'body' => 'Evidence quality low. Add additional photos before finalizing job.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 13 — Complaint Recovery Loop
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.complaint',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'complaint',
                        'severity' => 3,
                        'title' => 'Complaint received: log reason',
                        'body' => 'Log complaint reason clearly (what, where, severity).',
                    ]],
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'complaint',
                        'severity' => 3,
                        'title' => 'Offer re-clean',
                        'body' => 'Offer re-clean to recover trust and prevent churn.',
                    ]],
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'complaint',
                        'severity' => 3,
                        'title' => 'Schedule supervisor review',
                        'body' => 'Schedule supervisor/lead review for the next visit or re-clean.',
                    ]],
                    ['action' => 'queue_pending_action', 'params' => [
                        'action_type' => 'create_follow_up_job',
                        'title' => 'Create follow-up job (re-clean)',
                        'body' => 'Queued for human approval: create a follow-up job to address the complaint (re-clean).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 14 — Recurring Client Upsell
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.recurring_client', 'op' => '==', 'value' => true],
                    ['field' => 'payload.job_count_last_90_days', 'op' => '>=', 'value' => 6],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'upsell',
                        'severity' => 1,
                        'title' => 'Upsell opportunity for recurring client',
                        'body' => 'Offer deep clean add-on or extra service (oven, carpet, window clean).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 15 — Absent Client Pattern (weekly sweep)
            [
                'trigger_type' => 'schedule',
                'trigger_event' => 'schedule.weekly',
                'conditions_json' => json_encode([], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'retention',
                        'severity' => 2,
                        'title' => 'Recurring client may have paused',
                        'body' => 'Client may have paused service. Send rebooking message.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 16 — Cleaner Performance Pattern
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'payload.cleaner_id', 'op' => 'exists', 'value' => true],
                    ['field' => 'payload.job_failures_last_30_days', 'op' => '>=', 'value' => 3],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'run_analysis', 'params' => [
                        'analysis_type' => 'cleaner_performance_pattern',
                        'title' => 'Cleaner Performance Pattern',
                        'summary' => 'Flags repeated failures for the same cleaner in the last 30 days.',
                    ]],
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'performance',
                        'severity' => 2,
                        'title' => 'Performance issue detected',
                        'body' => 'Performance issue detected. Recommend supervisor review.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 17 — Property Problem Detection (keywords)
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'notes', 'op' => 'contains_any', 'value' => ['mold','pets','damage','clutter','renovation']],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'property_note',
                        'severity' => 1,
                        'title' => 'Property condition note suggested',
                        'body' => 'Repeated condition keywords detected (mold/pets/damage/clutter/renovation). Create property condition note for the client record.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 18 — Long Job Duration Detector
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.job.completed',
                'conditions_json' => json_encode([
                    ['field' => 'duration_ratio', 'op' => '>=', 'value' => 1.4],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'pricing',
                        'severity' => 1,
                        'title' => 'Job taking longer than expected',
                        'body' => 'Job taking longer than expected. Adjust pricing or service duration.',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 19 — Evidence → Marketing Asset
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.evidence.uploaded',
                'conditions_json' => json_encode([
                    ['field' => 'payload.image_quality_high', 'op' => '==', 'value' => true],
                    ['field' => 'payload.client_opt_in_marketing', 'op' => '==', 'value' => true],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'marketing_asset',
                        'severity' => 0,
                        'title' => 'Marketing asset available',
                        'body' => 'Convert before/after photos into a marketing post (client opted-in).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],

            // Rule 20 — Loyalty Reward Trigger
            [
                'trigger_type' => 'signal',
                'trigger_event' => 'work.invoice.paid',
                'conditions_json' => json_encode([
                    ['field' => 'payload.jobs_completed', 'op' => '>=', 'value' => 20],
                ], JSON_UNESCAPED_UNICODE),
                'actions_json' => json_encode([
                    ['action' => 'create_suggestion', 'params' => [
                        'suggestion_type' => 'loyalty',
                        'severity' => 1,
                        'title' => 'Loyalty reward suggested',
                        'body' => 'Offer loyalty reward (free add-on or discount).',
                    ]],
                ], JSON_UNESCAPED_UNICODE),
            ],
        ];
    }
}
