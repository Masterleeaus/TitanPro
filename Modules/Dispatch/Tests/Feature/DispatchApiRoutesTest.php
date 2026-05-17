<?php

declare(strict_types=1);

namespace Modules\Dispatch\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DispatchApiRoutesTest extends TestCase
{
    public function test_dispatch_api_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('dispatch.api.work-orders.index'));
        $this->assertTrue(Route::has('dispatch.api.work-orders.store'));
        $this->assertTrue(Route::has('dispatch.api.calendar'));
        $this->assertTrue(Route::has('dispatch.api.schedule'));
    }
}
