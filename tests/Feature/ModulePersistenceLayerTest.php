<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Modules\TitanCore\Services\Upgrade\UpgradeEngine;
use Modules\TitanCore\Support\ModuleDependencyGraph;

test('module persistence tables exist with expected columns', function () {
    expect(Schema::hasTable('titan_modules'))->toBeTrue();
    expect(Schema::hasColumns('titan_modules', [
        'name',
        'version',
        'status',
        'installed_at',
        'enabled_at',
        'disabled_at',
    ]))->toBeTrue();

    expect(Schema::hasTable('titan_module_manifest_snapshots'))->toBeTrue();
    expect(Schema::hasColumns('titan_module_manifest_snapshots', [
        'module_name',
        'manifest_hash',
        'payload',
        'synced_at',
    ]))->toBeTrue();

    expect(Schema::hasTable('titan_module_upgrade_tracking'))->toBeTrue();
    expect(Schema::hasColumns('titan_module_upgrade_tracking', [
        'module_name',
        'upgrade_file',
        'status',
        'ran_at',
    ]))->toBeTrue();
});

test('modules enable and disable update a single titan_modules row with timestamps', function () {
    $moduleName = 'LifecycleModule_'.uniqid();

    $module = new class
    {
        public bool $enabled = false;

        public function isEnabled(): bool
        {
            return $this->enabled;
        }

        public function enable(): void
        {
            $this->enabled = true;
        }

        public function disable(): void
        {
            $this->enabled = false;
        }

        public function get(string $key): ?string
        {
            return $key === 'version' ? '1.2.3' : null;
        }
    };

    app()->instance('modules', new class($moduleName, $module)
    {
        public function __construct(
            private readonly string $moduleName,
            private readonly object $module
        ) {}

        public function find(string $name): ?object
        {
            return $name === $this->moduleName ? $this->module : null;
        }
    });

    app()->instance(ModuleDependencyGraph::class, new class
    {
        public function build(): void {}
        public function validateEnableModule(string $module): array
        {
            return ['errors' => [], 'warnings' => []];
        }
        public function getNodes(): array
        {
            return [];
        }
    });

    $this->artisan('modules:enable', ['module' => [$moduleName], '--force' => true])->assertExitCode(0);

    $enabledRow = DB::table('titan_modules')->where('name', $moduleName)->first();
    expect($enabledRow)->not->toBeNull();
    expect($enabledRow->status)->toBe('enabled');
    expect($enabledRow->installed_at)->not->toBeNull();
    expect($enabledRow->enabled_at)->not->toBeNull();
    expect($enabledRow->disabled_at)->toBeNull();

    $this->artisan('modules:disable', ['module' => [$moduleName], '--force' => true])->assertExitCode(0);

    expect(DB::table('titan_modules')->where('name', $moduleName)->count())->toBe(1);

    $disabledRow = DB::table('titan_modules')->where('name', $moduleName)->first();
    expect($disabledRow->status)->toBe('disabled');
    expect($disabledRow->disabled_at)->not->toBeNull();
});

test('manifest snapshots are deduplicated by hash and preserve changed history', function () {
    $moduleName = 'ManifestSyncModule_'.uniqid();
    $moduleDir = base_path('Modules/'.$moduleName);

    mkdir($moduleDir, 0755, true);
    file_put_contents($moduleDir.'/module.json', json_encode([
        'name' => $moduleName,
        'version' => '1.0.0',
        'providers' => ['Illuminate\Support\ServiceProvider'],
    ]));

    try {
        $this->artisan('modules:manifest-cache')->assertExitCode(0);

        expect(DB::table('titan_module_manifest_snapshots')->where('module_name', $moduleName)->count())->toBe(1);

        $this->artisan('modules:manifest-cache')->assertExitCode(0);

        expect(DB::table('titan_module_manifest_snapshots')->where('module_name', $moduleName)->count())->toBe(1);

        file_put_contents($moduleDir.'/module.json', json_encode([
            'name' => $moduleName,
            'version' => '2.0.0',
            'providers' => ['Illuminate\Support\ServiceProvider'],
        ]));

        $this->artisan('modules:manifest-cache')->assertExitCode(0);

        $rows = DB::table('titan_module_manifest_snapshots')
            ->where('module_name', $moduleName)
            ->orderBy('id')
            ->get(['manifest_hash']);

        expect($rows)->toHaveCount(2);
        expect($rows[0]->manifest_hash)->not->toBe($rows[1]->manifest_hash);
    } finally {
        File::deleteDirectory($moduleDir);
    }
});

test('upgrade tracking skips already successful upgrade files', function () {
    $moduleName = 'UpgradeTrackingModule_'.uniqid();
    $moduleDir = base_path('Modules/'.$moduleName);
    $logFile = sys_get_temp_dir().'/'.$moduleName.'_upgrade.log';
    @unlink($logFile);

    mkdir($moduleDir.'/Upgrade/Migrations', 0755, true);
    file_put_contents($moduleDir.'/module.json', json_encode([
        'name' => $moduleName,
        'version' => '1.0.0',
        'providers' => ['Illuminate\Support\ServiceProvider'],
    ]));
    file_put_contents(
        $moduleDir.'/Upgrade/Migrations/2026_01_01_000001_once.php',
        "<?php\nreturn new class { public function run(): void { file_put_contents('{$logFile}', \"ran\\n\", FILE_APPEND); } };"
    );

    try {
        $engine = (new UpgradeEngine())->setMaintenanceMode(false);
        expect($engine->run($moduleName)['ok'])->toBeTrue();
        expect($engine->run($moduleName)['ok'])->toBeTrue();

        $lines = array_values(array_filter(explode("\n", (string) @file_get_contents($logFile))));
        expect($lines)->toBe(['ran']);

        $tracking = DB::table('titan_module_upgrade_tracking')
            ->where('module_name', $moduleName)
            ->where('upgrade_file', '2026_01_01_000001_once.php')
            ->first();

        expect($tracking)->not->toBeNull();
        expect($tracking->status)->toBe('success');
    } finally {
        File::deleteDirectory($moduleDir);
        @unlink($logFile);
    }
});

test('failed upgrade is tracked with failed status and error message', function () {
    $moduleName = 'UpgradeFailureModule_'.uniqid();
    $moduleDir = base_path('Modules/'.$moduleName);

    mkdir($moduleDir.'/Upgrade/Migrations', 0755, true);
    file_put_contents($moduleDir.'/module.json', json_encode([
        'name' => $moduleName,
        'version' => '1.0.0',
        'providers' => ['Illuminate\Support\ServiceProvider'],
    ]));
    file_put_contents(
        $moduleDir.'/Upgrade/Migrations/2026_01_01_000001_fail.php',
        "<?php\nreturn new class { public function run(): void { throw new RuntimeException('upgrade exploded'); } };"
    );

    try {
        expect((new UpgradeEngine())->setMaintenanceMode(false)->run($moduleName)['ok'])->toBeFalse();

        $tracking = DB::table('titan_module_upgrade_tracking')
            ->where('module_name', $moduleName)
            ->where('upgrade_file', '2026_01_01_000001_fail.php')
            ->first();

        expect($tracking)->not->toBeNull();
        expect($tracking->status)->toBe('failed');
        expect((string) $tracking->error_message)->toContain('upgrade exploded');
    } finally {
        File::deleteDirectory($moduleDir);
    }
});
