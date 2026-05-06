<?php

use Illuminate\Support\Facades\File;

/**
 * Verifies `modules:health` behaviour across multiple scenarios.
 */

// ── Healthy module ────────────────────────────────────────────────────────────

test('modules:health reports healthy for a module with valid manifest and loadable provider', function () {
    $dir = sys_get_temp_dir() . '/titan_test_modules_' . uniqid();
    $moduleDir = $dir . '/DemoModule';
    mkdir($moduleDir, 0755, true);

    // Write a minimal module.json that references a class that does exist
    file_put_contents($moduleDir . '/module.json', json_encode([
        'name'      => 'DemoModule',
        'providers' => ['Illuminate\Support\ServiceProvider'], // always exists
    ]));

    $this->artisan('modules:health', ['--module' => 'DemoModule'])
        ->expectsOutputToContain('DemoModule')
        ->assertExitCode(0);

    File::deleteDirectory($dir);
})->skip('uses temp dir outside Modules/; integration-only');

test('modules:health exits 1 when a module is missing module.json', function () {
    // Use the real Modules directory and pick a sub-directory that has no module.json
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    // Create a temporary placeholder module with no module.json
    $tempModule = $modulesBase . '/_TestHealthEmpty_' . uniqid();
    mkdir($tempModule, 0755, true);

    try {
        $this->artisan('modules:health', ['--module' => basename($tempModule)])
            ->assertExitCode(1);
    } finally {
        rmdir($tempModule);
    }
});

test('modules:health --json outputs valid JSON', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    // Use Artisan::call + Artisan::output() to capture the JSON output.
    \Illuminate\Support\Facades\Artisan::call('modules:health', ['--json' => true]);
    $raw = \Illuminate\Support\Facades\Artisan::output();

    // The output must be valid JSON regardless of exit code.
    $decoded = json_decode($raw, true);
    expect($decoded)->toBeArray();

    // Each entry should have 'healthy' and 'checks' keys.
    if (! empty($decoded)) {
        $first = array_values($decoded)[0];
        expect($first)->toHaveKeys(['healthy', 'checks']);
    }
});

test('modules:health exits 1 when provider class does not exist', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $tempModule = $modulesBase . '/_TestHealthBadProvider_' . uniqid();
    mkdir($tempModule, 0755, true);

    file_put_contents($tempModule . '/module.json', json_encode([
        'name'      => 'BadProvider',
        'providers' => ['Modules\NonExistent\Providers\FakeServiceProvider'],
    ]));

    try {
        $this->artisan('modules:health', ['--module' => basename($tempModule)])
            ->assertExitCode(1);
    } finally {
        unlink($tempModule . '/module.json');
        rmdir($tempModule);
    }
});
