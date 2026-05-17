<?php

namespace Modules\TitanEchoAssist\Services;

class WorkcoreChecklistDataService
{
    public function __construct(private readonly WorkcorePortalDataService $workcorePortalDataService) {}

    public function getChecklistForJob(int $jobId, int $companyId): array
    {
        return $this->workcorePortalDataService->getJobChecklist($jobId, $companyId);
    }

    public function getCompletionPercentage(int $jobId, int $companyId): float
    {
        $items = $this->getChecklistForJob($jobId, $companyId);
        $total = count($items);

        if ($total === 0) {
            return 0.0;
        }

        $completed = count(array_filter($items, fn (array $item): bool => ! empty($item['completed_at'])));

        return round(($completed / $total) * 100, 2);
    }
}
