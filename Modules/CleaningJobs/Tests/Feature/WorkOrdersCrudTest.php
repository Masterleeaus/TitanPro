<?php

namespace Modules\CleaningJobs\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CleaningJobs\Models\WorkOrder;

class WorkOrdersCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_orders()
    {
        WorkOrder::factory()->count(3)->create();
        $resp = $this->actingAs(\App\Models\User::factory()->create())
            ->get('/cleaning-jobs/orders');
        $resp->assertStatus(200);
    }
}
