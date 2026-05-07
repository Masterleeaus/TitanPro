<?php

use Illuminate\Support\Facades\File;

/**
 * Tests for the modules:blueprint:doctor artisan command.
 */
describe('modules:blueprint:doctor command', function () {

    test('exits successfully when modules directory exists', function () {
        $this->artisan('modules:blueprint:doctor')
            ->assertExitCode(0);
    });

    test('reports error when --module refers to a non-existent module', function () {
        $this->artisan('modules:blueprint:doctor', ['--module' => '_NonExistentModule_'])
            ->assertExitCode(1);
    });

    test('accepts --strict flag without error on a valid modules dir', function () {
        $this->artisan('modules:blueprint:doctor', ['--strict' => true])
            ->assertExitCode(0);
    });

    test('validates a module directory with a valid module.manifest.json', function () {
        $modulesBase = base_path('Modules');
        $tempDir = $modulesBase . '/_BlueprintDoctorTest_' . uniqid();
        mkdir($tempDir, 0755, true);

        file_put_contents($tempDir . '/module.manifest.json', json_encode([
            'name'    => '_BlueprintDoctorTest_',
            'version' => '1.0.0',
        ]));

        try {
            $this->artisan('modules:blueprint:doctor', ['--module' => basename($tempDir)])
                ->assertExitCode(0);
        } finally {
            File::deleteDirectory($tempDir);
        }
    });

    test('exits 1 for a module directory with an invalid module.manifest.json', function () {
        $modulesBase = base_path('Modules');
        $tempDir = $modulesBase . '/_BlueprintDoctorBad_' . uniqid();
        mkdir($tempDir . '/manifests', 0755, true);

        // Write malformed JSON
        file_put_contents($tempDir . '/module.manifest.json', '{broken json');

        try {
            $this->artisan('modules:blueprint:doctor', ['--module' => basename($tempDir)])
                ->assertExitCode(1);
        } finally {
            File::deleteDirectory($tempDir);
        }
    });

});
