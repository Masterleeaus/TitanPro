<?php

namespace Modules\GroundZeroOps\Tests\Integration;

use Modules\GroundZeroOps\Providers\EventServiceProvider;
use Modules\GroundZeroOps\Providers\FilamentServiceProvider;
use Modules\GroundZeroOps\Providers\GroundZeroOpsServiceProvider;
use Modules\GroundZeroOps\Providers\RouteServiceProvider;
use PHPUnit\Framework\TestCase;

class ModuleBootTest extends TestCase
{
    public function test_all_required_providers_exist(): void
    {
        $this->assertTrue(class_exists(GroundZeroOpsServiceProvider::class));
        $this->assertTrue(class_exists(RouteServiceProvider::class));
        $this->assertTrue(class_exists(EventServiceProvider::class));
        $this->assertTrue(class_exists(FilamentServiceProvider::class));
    }

    public function test_migrations_are_present_for_all_required_tables(): void
    {
        $migrationDir = dirname(__DIR__, 2) . '/Database/Migrations';

        $this->assertFileExists($migrationDir . '/create_ground_zero_jobs_table.php');
        $this->assertFileExists($migrationDir . '/create_ground_zero_shifts_table.php');
        $this->assertFileExists($migrationDir . '/create_ground_zero_incidents_table.php');
        $this->assertFileExists($migrationDir . '/create_ground_zero_dispatches_table.php');
    }
}
