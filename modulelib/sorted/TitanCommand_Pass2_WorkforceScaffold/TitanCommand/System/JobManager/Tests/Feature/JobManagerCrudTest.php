<?php

namespace App\Extensions\TitanCommand\System\JobManager\Tests\Feature;

    use RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Extensions\TitanCommand\System\JobManager\Entities\WorkOrder;
use Tests\TestCase;




class JobManagerCrudTest extends TestCase
{

    /** @test */
    public function it_lists_orders()
    {
        WorkOrder::factory()->count(3)->create();
        $resp = $this->actingAs(\App\Models\User::factory()->create())
            ->get('/jobmanager/orders');
        $resp->assertStatus(200);
    }
}
