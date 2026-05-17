<?php

namespace Modules\Complaint\AI\Tools;

use Modules\Complaint\Services\ComplaintAnalysisService;

class AnalyseComplaintTool
{
    public function __construct(private readonly ComplaintAnalysisService $analysisService)
    {
    }

    /**
     * @param  array{subject?: string, description?: string}  $input
     * @return array{severity: string, category: string, resolution_suggestion: string}
     */
    public function execute(array $input): array
    {
        return $this->analysisService->analyse(
            (string) ($input['subject'] ?? ''),
            (string) ($input['description'] ?? '')
        );
    }
}
