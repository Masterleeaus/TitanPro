<?php

namespace App\Support\GroundZero;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Collection;

class CommandCatalog
{
    public function snapshot(?int $organizationId): array
    {
        if ($organizationId === null) {
            return $this->emptySnapshot();
        }

        $jobs = Job::query()->where('organization_id', $organizationId);
        $todayJobs = (clone $jobs)->whereBetween('scheduled_at', [now()->startOfDay(), now()->endOfDay()]);
        $weekJobs = (clone $jobs)->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()]);

        return [
            'today_jobs' => (clone $todayJobs)->count(),
            'active_jobs' => (clone $jobs)->whereIn('status', [
                Job::STATUS_SCHEDULED,
                Job::STATUS_ASSIGNED,
                Job::STATUS_EN_ROUTE,
                Job::STATUS_ARRIVED,
                Job::STATUS_IN_PROGRESS,
                Job::STATUS_QUALITY_CHECK,
            ])->count(),
            'unassigned_jobs' => (clone $jobs)
                ->where('status', Job::STATUS_SCHEDULED)
                ->whereNull('assigned_to')
                ->count(),
            'quality_checks' => (clone $jobs)->where('status', Job::STATUS_QUALITY_CHECK)->count(),
            'completed_this_week' => (clone $weekJobs)->whereIn('status', [Job::STATUS_COMPLETED, Job::STATUS_INVOICED, Job::STATUS_PAID])->count(),
            'overdue_invoices' => Invoice::query()->where('organization_id', $organizationId)->where('status', 'overdue')->count(),
            'open_estimates' => Estimate::query()->where('organization_id', $organizationId)->whereIn('status', ['draft', 'sent', 'pending'])->count(),
            'customers' => Customer::query()->where('organization_id', $organizationId)->count(),
            'team_members' => User::query()->where('organization_id', $organizationId)->count(),
        ];
    }

    public function recommendedCommands(?int $organizationId): Collection
    {
        $snapshot = $this->snapshot($organizationId);

        return collect([
            [
                'label' => 'Summarise today',
                'prompt' => 'Summarise today’s cleaning schedule, risks, and priority actions.',
                'intent' => 'daily_briefing',
                'priority' => 'High',
                'hint' => $snapshot['today_jobs'].' jobs scheduled today',
            ],
            [
                'label' => 'Find unassigned jobs',
                'prompt' => 'Show unassigned scheduled jobs and recommend who should take them.',
                'intent' => 'assignment_review',
                'priority' => $snapshot['unassigned_jobs'] > 0 ? 'High' : 'Normal',
                'hint' => $snapshot['unassigned_jobs'].' waiting for assignment',
            ],
            [
                'label' => 'Check quality work',
                'prompt' => 'List jobs in quality check and tell me what needs approval.',
                'intent' => 'quality_review',
                'priority' => $snapshot['quality_checks'] > 0 ? 'High' : 'Normal',
                'hint' => $snapshot['quality_checks'].' jobs in quality check',
            ],
            [
                'label' => 'Review money',
                'prompt' => 'Show overdue invoices, open estimates, and money actions for today.',
                'intent' => 'finance_review',
                'priority' => $snapshot['overdue_invoices'] > 0 ? 'High' : 'Normal',
                'hint' => $snapshot['overdue_invoices'].' overdue invoices',
            ],
        ]);
    }

    public function commandResponse(string $prompt, ?int $organizationId): array
    {
        $snapshot = $this->snapshot($organizationId);
        $normalised = str($prompt)->lower()->toString();

        $intent = 'general';
        $title = 'GroundZero readout';
        $message = 'I have prepared the current business snapshot. Connect the AI action executor to let GroundZero safely run live changes.';

        if (str_contains($normalised, 'today') || str_contains($normalised, 'schedule')) {
            $intent = 'daily_briefing';
            $title = 'Today’s operating brief';
            $message = "Today has {$snapshot['today_jobs']} scheduled jobs, {$snapshot['unassigned_jobs']} unassigned jobs, and {$snapshot['quality_checks']} jobs waiting for quality review.";
        } elseif (str_contains($normalised, 'unassigned') || str_contains($normalised, 'assign')) {
            $intent = 'assignment_review';
            $title = 'Assignment review';
            $message = "There are {$snapshot['unassigned_jobs']} scheduled jobs without an assigned cleaner. Review Titan Pro job assignment before sending changes to TitanGo.";
        } elseif (str_contains($normalised, 'invoice') || str_contains($normalised, 'money') || str_contains($normalised, 'finance')) {
            $intent = 'finance_review';
            $title = 'Money actions';
            $message = "There are {$snapshot['overdue_invoices']} overdue invoices and {$snapshot['open_estimates']} open estimates needing follow-up.";
        } elseif (str_contains($normalised, 'quality') || str_contains($normalised, 'check')) {
            $intent = 'quality_review';
            $title = 'Quality review';
            $message = "There are {$snapshot['quality_checks']} jobs in quality check. Approvals should stay auditable through Titan Pro records.";
        }

        return compact('intent', 'title', 'message', 'snapshot');
    }

    private function emptySnapshot(): array
    {
        return [
            'today_jobs' => 0,
            'active_jobs' => 0,
            'unassigned_jobs' => 0,
            'quality_checks' => 0,
            'completed_this_week' => 0,
            'overdue_invoices' => 0,
            'open_estimates' => 0,
            'customers' => 0,
            'team_members' => 0,
        ];
    }
}
