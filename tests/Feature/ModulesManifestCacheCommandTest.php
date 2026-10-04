<?php

use Illuminate\Support\Facades\File;

/**
 * Tests for the modules:manifest-cache artisan command.
 */
describe('modules:manifest-cache command', function () {

    beforeEach(function () {
        $this->defaultCachePath = base_path('bootstrap/cache/titan_manifests.php');
    });

    afterEach(function () {
        // Clean up the default cache file after each test
        if (file_exists($this->defaultCachePath)) {
            unlink($this->defaultCachePath);
        }
    });

    test('writes the cache file and exits 0', function () {
        $this->artisan('modules:manifest-cache')
            ->assertExitCode(0);

        expect(file_exists($this->defaultCachePath))->toBeTrue('Cache file should have been written');
    });

    test('written cache file is valid PHP that returns an array', function () {
        $this->artisan('modules:manifest-cache')
            ->assertExitCode(0);

        $data = require $this->defaultCachePath;
        expect($data)->toBeArray();
    });

    test('written cache array keys are module names', function () {
        $this->artisan('modules:manifest-cache')
            ->assertExitCode(0);

        $data = require $this->defaultCachePath;

        foreach (array_keys($data) as $moduleName) {
            expect($moduleName)->toBeString()->not->toBeEmpty();
        }
    });

    test('--clear removes the cache file', function () {
        // Write it first
        $this->artisan('modules:manifest-cache')->assertExitCode(0);
        expect(file_exists($this->defaultCachePath))->toBeTrue();

        // Now clear it
        $this->artisan('modules:manifest-cache', ['--clear' => true])
            ->assertExitCode(0);

        expect(file_exists($this->defaultCachePath))->toBeFalse('Cache file should have been deleted');
    });

    test('--clear exits 0 even when cache file does not exist', function () {
        // Ensure the file is absent
        if (file_exists($this->defaultCachePath)) {
            unlink($this->defaultCachePath);
        }

        $this->artisan('modules:manifest-cache', ['--clear' => true])
            ->assertExitCode(0);
    });

    test('output contains module count', function () {
        $this->artisan('modules:manifest-cache')
            ->expectsOutputToContain('cached')
            ->assertExitCode(0);
    });

});
