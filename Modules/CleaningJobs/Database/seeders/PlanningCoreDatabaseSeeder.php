<?php

namespace Modules\PlanningCore\Database\Seeders;

use Illuminate\Database\Seeder;

class PlanningCoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PlanningCorePermissionSeeder::class,
            ProjectStatusSeeder::class,
            ProjectSeeder::class,
            ProjectTaskSeeder::class,
            TimesheetSeeder::class,
            ResourceAllocationSeeder::class,
        ]);
    }
}
