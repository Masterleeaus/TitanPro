<?php

namespace Modules\JobManager\Tests\Feature;

    use RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\JobManager\Entities\WorkOrder;
use Tests\TestCase;


namespace Modules\JobManager\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\JobManager\Entities\WorkOrder;

class JobManagerCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_orders()
    {
        WorkOrder::factory()->count(3)->create();
        $resp = $this->actingAs(\App\Models\User::factory()->create())
            ->get('/jobmanager/orders');
        $resp->assertStatus(200);
    }
}
