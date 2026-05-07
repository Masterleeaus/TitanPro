<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Verifies `modules:production-check` behaviour across multiple scenarios.
 */

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Create a temporary module directory under the real Modules/ path.
 * Returns the absolute path to the new module directory.
 */
function makeModule(string $suffix, array $manifestData = [], bool $writeManifest = true): string
{
    $modulesBase = base_path('Modules');
    $dir = $modulesBase.'/_TestProdCheck_'.$suffix.'_'.uniqid();
    mkdir($dir, 0755, true);

    if ($writeManifest) {
        $defaults = [
            'name'      => basename($dir),
            'providers' => ['Illuminate\Support\ServiceProvider'],
        ];
        file_put_contents($dir.'/module.json', json_encode(array_merge($defaults, $manifestData)));
    }

    return $dir;
}

/**
 * Remove a temporary module directory and all its contents.
 */
function removeModule(string $dir): void
{
    File::deleteDirectory($dir);
}

// ── Clean / passing module ─────────────────────────────────────────────────────

test('production-check exits 0 for a clean module with valid manifest', function () {
    $dir = makeModule('Clean');

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(0);
    } finally {
        removeModule($dir);
    }
});

// ── Manifest schema validation ─────────────────────────────────────────────────

test('production-check exits 1 when module.json is absent', function () {
    $dir = makeModule('NoManifest', writeManifest: false);

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check exits 1 when module.json is invalid JSON', function () {
    $dir = makeModule('BadJson', writeManifest: false);
    file_put_contents($dir.'/module.json', '{ not valid json }');

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check exits 1 when module.json is missing required fields', function () {
    $dir = makeModule('MissingFields', writeManifest: false);
    // Write a manifest without 'name' or 'providers'
    file_put_contents($dir.'/module.json', json_encode(['alias' => 'test']));

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

// ── Scaffold / demo file detection ────────────────────────────────────────────

test('production-check exits 1 when a *.stub file is present', function () {
    $dir = makeModule('Stub');
    file_put_contents($dir.'/SomeClass.stub', '// scaffold');

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check exits 1 when a *.demo file is present', function () {
    $dir = makeModule('Demo');
    file_put_contents($dir.'/data.demo', 'demo content');

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check exits 1 when a *.scaffold file is present', function () {
    $dir = makeModule('Scaffold');
    file_put_contents($dir.'/Setup.scaffold', 'scaffold content');

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check exits 1 when a *.example file is present', function () {
    $dir = makeModule('Example');
    file_put_contents($dir.'/config.example', '# example config');

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

// ── Required env vars ─────────────────────────────────────────────────────────

test('production-check exits 1 when a required env var is missing', function () {
    $dir = makeModule('MissingEnv', [
        'required_env' => ['TITAN_MISSING_ENV_VAR_THAT_DOES_NOT_EXIST'],
    ]);

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check exits 0 when all required env vars are present', function () {
    // APP_KEY is always set during tests
    $dir = makeModule('PresentEnv', [
        'required_env' => ['APP_KEY'],
    ]);

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(0);
    } finally {
        removeModule($dir);
    }
});

// ── Provider registration check ───────────────────────────────────────────────

test('production-check exits 1 when a provider is not registered in bootstrap/providers.php', function () {
    $dir = makeModule('UnregisteredProvider', [
        'providers' => ['Modules\NonExistent\Providers\UnregisteredServiceProvider'],
    ]);

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(1);
    } finally {
        removeModule($dir);
    }
});

test('production-check passes provider check for a provider present in bootstrap/providers.php', function () {
    // App\Providers\AppServiceProvider is always in bootstrap/providers.php
    $dir = makeModule('RegisteredProvider', [
        'providers' => ['App\Providers\AppServiceProvider'],
    ]);

    try {
        $this->artisan('modules:production-check', ['--module' => basename($dir)])
            ->assertExitCode(0);
    } finally {
        removeModule($dir);
    }
});

// ── JSON output ───────────────────────────────────────────────────────────────

test('production-check --json outputs valid JSON with expected structure', function () {
    $dir = makeModule('Json');

    try {
        Artisan::call('modules:production-check', [
            '--module' => basename($dir),
            '--json'   => true,
        ]);
        $raw     = Artisan::output();
        $decoded = json_decode($raw, true);

        expect($decoded)->toBeArray();
        expect($decoded)->toHaveKey('status');
        expect($decoded)->toHaveKey('modules');
        expect($decoded['status'])->toBeIn(['PASS', 'FAIL']);
    } finally {
        removeModule($dir);
    }
});

test('production-check --json reports FAIL status when blockers are present', function () {
    $dir = makeModule('JsonFail', writeManifest: false);

    try {
        Artisan::call('modules:production-check', [
            '--module' => basename($dir),
            '--json'   => true,
        ]);
        $raw     = Artisan::output();
        $decoded = json_decode($raw, true);

        expect($decoded['status'])->toBe('FAIL');
    } finally {
        removeModule($dir);
    }
});
