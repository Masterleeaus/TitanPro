<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use Modules\TitanEchoAssist\Services\WorkcoreSchedulingDataService;
use PHPUnit\Framework\TestCase;

class WorkcoreSchedulingDataServiceTest extends TestCase
{
    public function test_get_next_visit_returns_first_upcoming_visit(): void
    {
        $workcore = new class extends WorkcorePortalDataService
        {
            public function getUpcomingVisits(int $customerId, int $companyId, int $limit = 5): array
            {
                return [
                    ['id' => 10, 'status' => 'scheduled', 'scheduled_at' => '2026-05-18 09:00:00'],
                    ['id' => 11, 'status' => 'assigned', 'scheduled_at' => '2026-05-20 09:00:00'],
                ];
            }
        };

        $service = new WorkcoreSchedulingDataService($workcore);
        $nextVisit = $service->getNextVisit(5, 9);

        $this->assertSame(10, $nextVisit['id']);
        $this->assertSame('scheduled', $nextVisit['status']);
    }
}
