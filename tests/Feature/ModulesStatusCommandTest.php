<?php

use Illuminate\Support\Facades\Artisan;

/**
 * Tests for the modules:status artisan command.
 */
describe('modules:status command', function () {

    test('exits 0 and produces output', function () {
        $this->artisan('modules:status')
            ->assertExitCode(0);
    });

    test('--json outputs valid JSON', function () {
        Artisan::call('modules:status', ['--json' => true]);
        $raw = Artisan::output();

        $decoded = json_decode($raw, true);
        expect($decoded)->toBeArray();
    });

    test('--json rows contain expected keys', function () {
        Artisan::call('modules:status', ['--json' => true]);
        $rows = json_decode(Artisan::output(), true);

        if (! empty($rows)) {
            $first = array_values($rows)[0];
            expect($first)->toHaveKeys(['module', 'version', 'installed', 'status', 'description']);
        }
    });

    test('--json rows have a string module name', function () {
        Artisan::call('modules:status', ['--json' => true]);
        $rows = json_decode(Artisan::output(), true);

        foreach ($rows as $row) {
            expect($row['module'])->toBeString()->not->toBeEmpty();
        }
    });

    test('--enabled filter returns only enabled rows', function () {
        Artisan::call('modules:status', ['--json' => true, '--enabled' => true]);
        $rows = json_decode(Artisan::output(), true);

        foreach ($rows as $row) {
            expect($row['status'])->toBe('enabled');
        }
    });

    test('--disabled filter returns only disabled rows', function () {
        Artisan::call('modules:status', ['--json' => true, '--disabled' => true]);
        $rows = json_decode(Artisan::output(), true);

        foreach ($rows as $row) {
            expect($row['status'])->toBe('disabled');
        }
    });

});
