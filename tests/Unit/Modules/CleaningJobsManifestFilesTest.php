<?php

use Tests\TestCase;

class CleaningJobsManifestFilesTest extends TestCase
{
    public function test_core_manifests_are_valid_json_and_reference_existing_classes(): void
    {
        $base = dirname(__DIR__, 3).'/Modules/CleaningJobs/manifests';

        foreach (['automation', 'api', 'tenancy', 'workflows', 'module'] as $name) {
            $path = $base."/{$name}.manifest.json";
            $this->assertFileExists($path);
            $this->assertNotNull(json_decode((string) file_get_contents($path), true), "{$name}.manifest.json is invalid JSON");
        }

        $automation = json_decode((string) file_get_contents($base.'/automation.manifest.json'), true);
        $tenancy = json_decode((string) file_get_contents($base.'/tenancy.manifest.json'), true);
        $workflows = json_decode((string) file_get_contents($base.'/workflows.manifest.json'), true);

        foreach (['triggers', 'handlers', 'pipelines', 'schedulers'] as $section) {
            foreach ($automation[$section] ?? [] as $entry) {
                $this->assertTrue(class_exists($entry['class']));
            }
        }

        foreach ($tenancy['resolvers'] ?? [] as $entry) {
            $this->assertTrue(class_exists($entry['class']));
        }

        foreach ($tenancy['policies'] ?? [] as $entry) {
            $this->assertTrue(class_exists($entry['class']));
        }

        foreach ($workflows['workflows'] ?? [] as $entry) {
            $this->assertTrue(class_exists($entry['class']));
        }
    }
}
