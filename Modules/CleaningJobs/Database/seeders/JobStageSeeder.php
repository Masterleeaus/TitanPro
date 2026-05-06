<?php

namespace Modules\CleaningJobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CleaningJobs\Models\JobStage;

class JobStageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Backlog', 'slug' => 'backlog', 'order' => 10, 'is_default' => true],
            ['name' => 'Scheduled', 'slug' => 'scheduled', 'order' => 20],
            ['name' => 'In Progress', 'slug' => 'in_progress', 'order' => 30],
            ['name' => 'Inspection', 'slug' => 'inspection', 'order' => 40],
            ['name' => 'Completed', 'slug' => 'completed', 'order' => 50, 'is_completed' => true],
        ] as $stage) {
            JobStage::updateOrCreate(['slug' => $stage['slug']], $stage);
        }
    }
}
