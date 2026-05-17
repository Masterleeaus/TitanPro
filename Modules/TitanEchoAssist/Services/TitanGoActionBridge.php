<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class TitanGoActionBridge
{
    public function __construct(private readonly WorkcoreWorkerDataService $workerDataService) {}

    public function dispatch(string $actionKey, array $context): array
    {
        if (! $this->isEntitled($context)) {
            throw new RuntimeException('TitanGo license required.', 403);
        }

        return match ($actionKey) {
            'site_diary.create' => $this->dispatchSiteDiary($context),
            'compliance.scan' => $this->dispatchComplianceScan($context),
            'job.summary' => $this->dispatchJobSummary($context),
            'quote.risk_scan' => $this->dispatchQuoteRiskScan($context),
            default => [
                'preview' => "Unsupported TitanGo action [{$actionKey}]",
                'requiresConfirmation' => false,
                'result' => null,
            ],
        };
    }

    private function dispatchSiteDiary(array $context): array
    {
        $jobId = (int) ($context['job_id'] ?? 0);
        $companyId = (int) ($context['company_id'] ?? 0);
        $content = trim((string) ($context['content'] ?? $context['transcript'] ?? 'Voice diary entry'));
        $previewOnly = (bool) ($context['preview_only'] ?? false);

        if ($previewOnly) {
            return [
                'preview' => 'Create a site diary entry for the current job.',
                'requiresConfirmation' => true,
                'result' => null,
            ];
        }

        $result = $this->workerDataService->createSiteDiaryEntry($jobId, $companyId, $content);

        return [
            'preview' => 'Site diary entry created.',
            'requiresConfirmation' => true,
            'result' => $result,
        ];
    }

    private function dispatchComplianceScan(array $context): array
    {
        $jobId = (int) ($context['job_id'] ?? 0);
        $companyId = (int) ($context['company_id'] ?? 0);

        $checklist = $this->workerDataService->getJobChecklist($jobId, $companyId);

        $total = count($checklist);
        $completed = count(array_filter($checklist, fn (array $item): bool => ! empty($item['completed_at'])));

        return [
            'preview' => 'Run compliance scan against the current checklist.',
            'requiresConfirmation' => false,
            'result' => [
                'job_id' => $jobId,
                'total_items' => $total,
                'completed_items' => $completed,
                'open_items' => max(0, $total - $completed),
                'status' => ($total > 0 && $total === $completed) ? 'pass' : 'needs_attention',
            ],
        ];
    }

    private function dispatchJobSummary(array $context): array
    {
        $jobId = (int) ($context['job_id'] ?? 0);
        $companyId = (int) ($context['company_id'] ?? 0);

        $job = $this->workerDataService->getJobDetails($jobId, $companyId);
        $checklist = $this->workerDataService->getJobChecklist($jobId, $companyId);

        $summary = sprintf(
            'Job #%d is %s. Checklist completion: %d/%d.',
            $jobId,
            (string) ($job['status'] ?? 'unknown'),
            count(array_filter($checklist, fn (array $item): bool => ! empty($item['completed_at']))),
            count($checklist)
        );

        return [
            'preview' => 'Summarise current job progress and checklist state.',
            'requiresConfirmation' => false,
            'result' => [
                'summary' => $summary,
                'job' => $job,
                'checklist' => $checklist,
            ],
        ];
    }

    private function dispatchQuoteRiskScan(array $context): array
    {
        $jobId = (int) ($context['job_id'] ?? 0);

        $items = DB::table('job_line_items')
            ->where('job_id', $jobId)
            ->get(['id', 'name', 'quantity', 'unit_price'])
            ->map(fn ($item): array => (array) $item)
            ->all();

        $risks = [];
        foreach ($items as $item) {
            $lineTotal = ((float) ($item['quantity'] ?? 0)) * ((float) ($item['unit_price'] ?? 0));
            $name = strtolower((string) ($item['name'] ?? ''));

            if ($lineTotal >= 1000) {
                $risks[] = ['item_id' => $item['id'] ?? null, 'risk' => 'High-value line item', 'line_total' => $lineTotal];
            }

            if (str_contains($name, 'chemical') || str_contains($name, 'hazard')) {
                $risks[] = ['item_id' => $item['id'] ?? null, 'risk' => 'Potential hazardous material'];
            }
        }

        return [
            'preview' => 'Run quote risk scan on job line items.',
            'requiresConfirmation' => false,
            'result' => [
                'job_id' => $jobId,
                'risk_count' => count($risks),
                'risks' => $risks,
            ],
        ];
    }

    private function isEntitled(array $context): bool
    {
        if (array_key_exists('has_titango_license', $context)) {
            return (bool) $context['has_titango_license'];
        }

        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if (method_exists($user, 'hasAnyPermission') && $user->hasAnyPermission([
            'titango_field.field_jobs.view',
            'titango_field.field_jobs.view-any',
            'titango_field.field_jobs.update',
        ])) {
            return true;
        }

        $allowedPlans = (array) config('titango.entitled_plans', ['growth', 'pro']);

        return in_array($user->organization?->plan, $allowedPlans, true);
    }
}
