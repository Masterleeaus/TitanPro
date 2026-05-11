<?php

namespace Modules\TitanCommand\Tests\Unit;

use Tests\TestCase;

class ModuleStructureTest extends TestCase
{
    /** @test */
    public function module_json_is_present_and_valid(): void
    {
        $path = base_path('Modules/TitanCommand/module.json');
        $this->assertFileExists($path);

        $data = json_decode(file_get_contents($path), true);
        $this->assertSame('TitanCommand', $data['name']);
        $this->assertSame(1, $data['active']);
        $this->assertSame('groundzero', $data['filament_panel']);
    }

    /** @test */
    public function service_provider_class_exists(): void
    {
        $this->assertTrue(class_exists(\Modules\TitanCommand\Providers\TitanCommandServiceProvider::class));
    }

    /** @test */
    public function work_job_model_class_exists(): void
    {
        $this->assertTrue(class_exists(\Modules\TitanCommand\Models\Work\WorkJob::class));
    }

    /** @test */
    public function migrations_directory_contains_required_files(): void
    {
        $dir = base_path('Modules/TitanCommand/Database/Migrations');
        $this->assertDirectoryExists($dir);

        $required = [
            '2026_02_18_000100_create_work_jobs_table.php',
            '2026_02_18_000130_create_work_jobs_assignments_table.php',
            '2026_02_18_000140_create_work_jobs_checklists_table.php',
            '2026_02_18_000200_create_work_jobs_evidence_table.php',
            '2026_02_18_000210_create_work_jobs_inspections_table.php',
            '2026_02_18_000230_create_work_jobs_states_table.php',
            '2026_02_18_000240_create_work_jobs_reports_table.php',
        ];

        foreach ($required as $file) {
            $this->assertFileExists("{$dir}/{$file}", "Missing migration: {$file}");
        }
    }
}
