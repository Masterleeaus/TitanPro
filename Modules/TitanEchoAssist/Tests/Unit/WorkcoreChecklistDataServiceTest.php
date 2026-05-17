<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\WorkcoreChecklistDataService;
use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use PHPUnit\Framework\TestCase;

class WorkcoreChecklistDataServiceTest extends TestCase
{
    public function test_completion_percentage_uses_checklist_completion_ratio(): void
    {
        $workcore = new class extends WorkcorePortalDataService
        {
            public function getJobChecklist(int $jobId, int $companyId): array
            {
                return [
                    ['id' => 1, 'completed_at' => '2026-05-16 10:00:00'],
                    ['id' => 2, 'completed_at' => null],
                    ['id' => 3, 'completed_at' => '2026-05-16 11:00:00'],
                    ['id' => 4, 'completed_at' => null],
                ];
            }
        };

        $service = new WorkcoreChecklistDataService($workcore);

        $this->assertSame(50.0, $service->getCompletionPercentage(12, 22));
    }
}
