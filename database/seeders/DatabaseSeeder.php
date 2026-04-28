<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            DashboardLayoutPermissionsSeeder::class,
            DefaultDashboardLayoutSeeder::class,
            DemoSeeder::class,
        ]);
    }
}
