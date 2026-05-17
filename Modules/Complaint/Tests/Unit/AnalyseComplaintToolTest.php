<?php

namespace Modules\Complaint\Tests\Unit;

use Modules\Complaint\AI\Tools\AnalyseComplaintTool;
use Modules\Complaint\Services\ComplaintAnalysisService;
use PHPUnit\Framework\TestCase;

class AnalyseComplaintToolTest extends TestCase
{
    public function test_execute_returns_required_analysis_shape(): void
    {
        $tool = new AnalyseComplaintTool(new ComplaintAnalysisService());

        $result = $tool->execute([
            'subject' => 'Urgent refund requested',
            'description' => 'Customer is unhappy with cleaning quality and asked for refund',
        ]);

        $this->assertArrayHasKey('severity', $result);
        $this->assertArrayHasKey('category', $result);
        $this->assertArrayHasKey('resolution_suggestion', $result);
    }
}
