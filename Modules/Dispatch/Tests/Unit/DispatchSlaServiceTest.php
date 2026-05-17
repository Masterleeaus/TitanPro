<?php

declare(strict_types=1);

namespace Modules\Dispatch\Tests\Unit;

use Illuminate\Support\Carbon;
use Modules\Dispatch\Models\DispatchSlaPolicy;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Services\Workflow\DispatchSlaService;
use Tests\TestCase;

class DispatchSlaServiceTest extends TestCase
{
    public function test_it_detects_breached_completion_window(): void
    {
        DispatchSlaPolicy::query()->create([
            'name' => 'Urgent',
            'priority' => 'urgent',
            'response_minutes' => 15,
            'completion_minutes' => 30,
            'active' => true,
        ]);

        $workOrder = DispatchWorkOrder::query()->create([
            'title' => 'Urgent repair',
            'priority' => 'urgent',
            'status' => 'scheduled',
            'scheduled_for' => Carbon::now()->subHour(),
        ]);

        $this->assertTrue(app(DispatchSlaService::class)->isBreached($workOrder));
    }
}
