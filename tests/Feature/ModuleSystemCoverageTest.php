<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Modules\CallingAgent\Providers\ModuleServiceProvider as CallingAgentModuleServiceProvider;
use Modules\TitanCore\Services\Upgrade\UpgradeEngine;

test('module discovery finds temporary module and parses manifest via modules health command', function () {
    $moduleDir = base_path('Modules/_TestDiscovery_'.uniqid());
    mkdir($moduleDir, 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name' => basename($moduleDir),
        'providers' => ['Illuminate\Support\ServiceProvider'],
    ]));

    try {
        \Illuminate\Support\Facades\Artisan::call('modules:health', [
            '--module' => basename($moduleDir),
            '--json' => true,
        ]);

        $payload = json_decode(\Illuminate\Support\Facades\Artisan::output(), true);
        expect($payload)->toHaveKey(basename($moduleDir));
        expect($payload[basename($moduleDir)]['healthy'])->toBeTrue();
    } finally {
        File::deleteDirectory($moduleDir);
    }
});

test('calling agent provider boots and loads web api internal and tenant route files', function () {
    $loaded = [];

    $provider = new class(app(), $loaded) extends CallingAgentModuleServiceProvider
    {
        private array $loaded;

        public function __construct($app, array &$loaded)
        {
            $this->loaded = &$loaded;
            parent::__construct($app);
        }

        protected function loadRoutesFrom($path): void
        {
            $this->loaded[] = basename((string) $path);
        }

        protected function loadMigrationsFrom($paths): void {}
        protected function loadViewsFrom($path, $namespace): void {}
        protected function loadTranslationsFrom($path, $namespace): void {}
    };

    $provider->boot();

    expect($loaded)->toContain('web.php');
    expect($loaded)->toContain('api.php');
    expect($loaded)->toContain('internal.php');
    expect($loaded)->toContain('tenant.php');
});

test('upgrade engine executes upgrade files in order and persists upgrade history', function () {
    $moduleName = '_TestUpgrade_'.uniqid();
    $moduleDir = base_path('Modules/'.$moduleName);
    $orderFile = sys_get_temp_dir().'/'.$moduleName.'_order.log';
    @unlink($orderFile);

    mkdir($moduleDir.'/Upgrade/Migrations', 0755, true);
    mkdir($moduleDir.'/Upgrade/Scripts', 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name' => $moduleName,
        'version' => '9.9.9',
        'providers' => ['Illuminate\Support\ServiceProvider'],
    ]));

    file_put_contents($moduleDir.'/Upgrade/Migrations/2026_01_01_000001_first.php', "<?php\nreturn new class { public function run(): void { file_put_contents('{$orderFile}', \"first\\n\", FILE_APPEND); } };");
    file_put_contents($moduleDir.'/Upgrade/Migrations/2026_01_02_000001_second.php', "<?php\nreturn new class { public function run(): void { file_put_contents('{$orderFile}', \"second\\n\", FILE_APPEND); } };");

    if (! Schema::hasTable('upgrade_history')) {
        Schema::create('upgrade_history', function ($table) {
            $table->id();
            $table->string('module_name');
            $table->string('version')->nullable();
            $table->text('files_applied')->nullable();
            $table->string('snapshot_path')->nullable();
            $table->string('status')->default('success');
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
    }

    try {
        $result = (new UpgradeEngine())->setMaintenanceMode(false)->run($moduleName);

        expect($result['ok'])->toBeTrue();

        $order = array_values(array_filter(explode("\n", (string) @file_get_contents($orderFile))));
        expect($order)->toBe(['first', 'second']);

        $history = DB::table('upgrade_history')->where('module_name', $moduleName)->latest('id')->first();
        expect($history)->not->toBeNull();
        expect($history->version)->toBe('9.9.9');
    } finally {
        File::deleteDirectory($moduleDir);
        @unlink($orderFile);
    }
});
