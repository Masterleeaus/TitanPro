<?php

return [
    'enabled' => true,
    'tools' => [
        'analyse_complaint' => Modules\Complaint\AI\Tools\AnalyseComplaintTool::class,
        'draft_resolution_response' => Modules\Complaint\AI\Tools\DraftResolutionResponseTool::class,
    ],
];
