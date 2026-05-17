<?php

namespace Modules\GroundZeroOps\AI\Tools;

use Modules\GroundZeroOps\Services\DispatchService;

class SuggestDispatchTool
{
    public function __construct(private readonly DispatchService $dispatchService) {}

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function execute(array $input): array
    {
        $companyId = isset($input['company_id']) ? (int) $input['company_id'] : (int) (auth()->user()?->company_id ?? 0);
        $jobId = (int) ($input['job_id'] ?? 0);

        if ($companyId < 1 || $jobId < 1) {
            return [
                'job_id' => $jobId,
                'company_id' => $companyId,
                'ranked_technicians' => [],
            ];
        }

        return [
            'job_id' => $jobId,
            'company_id' => $companyId,
            'ranked_technicians' => $this->dispatchService->rankedTechniciansForJob($companyId, $jobId),
        ];
    }
}
