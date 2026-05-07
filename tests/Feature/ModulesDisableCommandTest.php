<?php

/**
 * Tests for the modules:disable artisan command.
 */
describe('modules:disable command', function () {

    test('exits 1 when the module is not installed', function () {
        $this->artisan('modules:disable', ['module' => ['_NotInstalledModule_']])
            ->assertExitCode(1);
    });

    test('exits 1 with error message for unknown module', function () {
        $this->artisan('modules:disable', ['module' => ['_GhostModule_']])
            ->expectsOutputToContain('not installed')
            ->assertExitCode(1);
    });

});
