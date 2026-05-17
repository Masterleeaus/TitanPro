<?php

namespace Tests\Feature;

use App\Support\GroundZero\TitanAiRuntime;
use Tests\TestCase;

class GroundZeroTitanZeroRuntimeTest extends TestCase
{
    public function test_runtime_status_is_safe_without_optional_modules(): void
    {
        $status = app(TitanAiRuntime::class)->integrationStatus();

        $this->assertArrayHasKey('engine', $status);
        $this->assertArrayHasKey('titanzero_present', $status);
        $this->assertArrayHasKey('titancore_present', $status);
    }
}
