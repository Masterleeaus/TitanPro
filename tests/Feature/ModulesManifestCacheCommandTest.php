<?php

use Illuminate\Support\Facades\File;

/**
 * Tests for the modules:manifest-cache artisan command.
 */
describe('modules:manifest-cache command', function () {

    /** Helper: resolve the default cache path used by the command. */
    $defaultCachePath = base_path('bootstrap/cache/titan_manifests.php');

    afterEach(function () use ($defaultCachePath) {
        // Clean up the default cache file after each test
        if (file_exists($defaultCachePath)) {
            unlink($defaultCachePath);
        }
    });

    test('writes the cache file and exits 0', function () use ($defaultCachePath) {
        $this->artisan('modules:manifest-cache')
            ->assertExitCode(0);

        expect(file_exists($defaultCachePath))->toBeTrue('Cache file should have been written');
    });

    test('written cache file is valid PHP that returns an array', function () use ($defaultCachePath) {
        $this->artisan('modules:manifest-cache')
            ->assertExitCode(0);

        $data = require $defaultCachePath;
        expect($data)->toBeArray();
    });

    test('written cache array keys are module names', function () use ($defaultCachePath) {
        $this->artisan('modules:manifest-cache')
            ->assertExitCode(0);

        $data = require $defaultCachePath;

        foreach (array_keys($data) as $moduleName) {
            expect($moduleName)->toBeString()->not->toBeEmpty();
        }
    });

    test('--clear removes the cache file', function () use ($defaultCachePath) {
        // Write it first
        $this->artisan('modules:manifest-cache')->assertExitCode(0);
        expect(file_exists($defaultCachePath))->toBeTrue();

        // Now clear it
        $this->artisan('modules:manifest-cache', ['--clear' => true])
            ->assertExitCode(0);

        expect(file_exists($defaultCachePath))->toBeFalse('Cache file should have been deleted');
    });

    test('--clear exits 0 even when cache file does not exist', function () use ($defaultCachePath) {
        // Ensure the file is absent
        if (file_exists($defaultCachePath)) {
            unlink($defaultCachePath);
        }

        $this->artisan('modules:manifest-cache', ['--clear' => true])
            ->assertExitCode(0);
    });

    test('output contains module count', function () use ($defaultCachePath) {
        $this->artisan('modules:manifest-cache')
            ->expectsOutputToContain('cached')
            ->assertExitCode(0);
    });

});
