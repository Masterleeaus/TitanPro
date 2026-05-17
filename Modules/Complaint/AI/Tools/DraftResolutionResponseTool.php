<?php

namespace Modules\Complaint\AI\Tools;

use Modules\Complaint\Services\ComplaintAnalysisService;

class DraftResolutionResponseTool
{
    public function __construct(private readonly ComplaintAnalysisService $analysisService)
    {
    }

    /**
     * @param  array{subject?: string, resolution_suggestion?: string, tone?: string}  $input
     * @return array{message: string, tone: string}
     */
    public function execute(array $input): array
    {
        return $this->analysisService->draftResponse(
            (string) ($input['subject'] ?? ''),
            (string) ($input['resolution_suggestion'] ?? 'We will review your complaint and follow up shortly.'),
            (string) ($input['tone'] ?? 'empathetic'),
        );
    }
}
