<?php

declare(strict_types=1);

namespace Modules\Dispatch\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Dispatch\Models\ServiceZone;
use Modules\Dispatch\Models\Shift;
use Modules\Dispatch\Models\TechnicianSkill;

class DispatchDemoSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['name' => 'Central', 'code' => 'CENTRAL'], ['name' => 'North', 'code' => 'NORTH'], ['name' => 'South', 'code' => 'SOUTH']] as $zone) {
            ServiceZone::query()->firstOrCreate(['code' => $zone['code']], $zone + ['active' => true]);
        }

        foreach (['General Service', 'Inspection', 'Maintenance', 'Installation'] as $skill) {
            TechnicianSkill::query()->firstOrCreate(['name' => $skill], ['active' => true]);
        }

        Shift::query()->firstOrCreate(['name' => 'Day Dispatch'], ['start_time' => '08:00:00', 'finish_time' => '17:00:00', 'publish' => 1, 'type' => 1]);
        Shift::query()->firstOrCreate(['name' => 'Evening Dispatch'], ['start_time' => '17:00:00', 'finish_time' => '22:00:00', 'publish' => 1, 'type' => 1]);
    }
}
