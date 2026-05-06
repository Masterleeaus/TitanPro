<?php

use Illuminate\Support\Facades\File;

/**
 * Verifies `modules:scan-production` behaviour:
 *  - finds *.stub, *.demo, *.scaffold, *.example files and exits 1
 *  - exits 0 when no blocker files are present
 */

test('scanner exits 0 and reports clean when no blocker files exist', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    // Create a clean temp module
    $tempModule = $modulesBase . '/_TestScanClean_' . uniqid();
    mkdir($tempModule, 0755, true);
    file_put_contents($tempModule . '/module.json', json_encode(['name' => 'TestScanClean']));
    file_put_contents($tempModule . '/Controller.php', '<?php // ok');

    try {
        $this->artisan('modules:scan-production', ['--module' => basename($tempModule)])
            ->expectsOutputToContain('No production-blocker files detected')
            ->assertExitCode(0);
    } finally {
        unlink($tempModule . '/module.json');
        unlink($tempModule . '/Controller.php');
        rmdir($tempModule);
    }
});

test('scanner exits 1 and reports BLOCKER for a *.stub file', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $tempModule = $modulesBase . '/_TestScanStub_' . uniqid();
    mkdir($tempModule, 0755, true);
    $stubFile = $tempModule . '/SomeController.stub';
    file_put_contents($stubFile, '// scaffold content');

    try {
        $this->artisan('modules:scan-production', ['--module' => basename($tempModule)])
            ->expectsOutputToContain('BLOCKER')
            ->assertExitCode(1);
    } finally {
        unlink($stubFile);
        rmdir($tempModule);
    }
});

test('scanner exits 1 for *.demo files', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $tempModule = $modulesBase . '/_TestScanDemo_' . uniqid();
    mkdir($tempModule, 0755, true);
    $demoFile = $tempModule . '/data.demo';
    file_put_contents($demoFile, 'demo data');

    try {
        $this->artisan('modules:scan-production', ['--module' => basename($tempModule)])
            ->expectsOutputToContain('BLOCKER')
            ->assertExitCode(1);
    } finally {
        unlink($demoFile);
        rmdir($tempModule);
    }
});

test('scanner exits 1 for *.scaffold files', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $tempModule = $modulesBase . '/_TestScanScaffold_' . uniqid();
    mkdir($tempModule, 0755, true);
    $scaffoldFile = $tempModule . '/Setup.scaffold';
    file_put_contents($scaffoldFile, 'scaffold');

    try {
        $this->artisan('modules:scan-production', ['--module' => basename($tempModule)])
            ->expectsOutputToContain('BLOCKER')
            ->assertExitCode(1);
    } finally {
        unlink($scaffoldFile);
        rmdir($tempModule);
    }
});

test('scanner exits 1 for *.example files', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $tempModule = $modulesBase . '/_TestScanExample_' . uniqid();
    mkdir($tempModule, 0755, true);
    $exampleFile = $tempModule . '/config.example';
    file_put_contents($exampleFile, '# example config');

    try {
        $this->artisan('modules:scan-production', ['--module' => basename($tempModule)])
            ->expectsOutputToContain('BLOCKER')
            ->assertExitCode(1);
    } finally {
        unlink($exampleFile);
        rmdir($tempModule);
    }
});

test('scanner --json reports blockers in JSON format', function () {
    $modulesBase = base_path('Modules');

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $tempModule = $modulesBase . '/_TestScanJson_' . uniqid();
    mkdir($tempModule, 0755, true);
    $stubFile = $tempModule . '/test.stub';
    file_put_contents($stubFile, '// stub');

    $moduleName = basename($tempModule);

    try {
        // Use Artisan::call + Artisan::output() for reliable output capture.
        $exitCode = \Illuminate\Support\Facades\Artisan::call('modules:scan-production', [
            '--module' => $moduleName,
            '--json'   => true,
        ]);
        $raw    = \Illuminate\Support\Facades\Artisan::output();
        $result = json_decode($raw, true);

        expect($exitCode)->toBe(1);
        expect($result)->toBeArray();
        expect($result['status'])->toBe('blockers_found');
        expect($result['blockers'])->toHaveKey($moduleName);
        expect($result['blockers'][$moduleName])->not->toBeEmpty();
    } finally {
        unlink($stubFile);
        rmdir($tempModule);
    }
});
