<?php

test('modules:doctor surfaces missing handler classes declared in automation manifests', function () {
    $modulesBase = base_path(config('titan-modules.path', 'Modules'));

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $moduleName = '_DoctorAutomationMissingHandler_'.uniqid();
    $moduleDir = $modulesBase.'/'.$moduleName;
    mkdir($moduleDir.'/manifests', 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name' => $moduleName,
        'active' => 1,
        'providers' => [],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    file_put_contents($moduleDir.'/manifests/automation.manifest.json', json_encode([
        'handlers' => [
            ['class' => "Modules\\{$moduleName}\\Automation\\Handlers\\MissingHandlerClass"],
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
        $this->artisan('modules:doctor', ['--skip-schema' => true])
            ->expectsOutputToContain("missing handler class Modules\\{$moduleName}\\Automation\\Handlers\\MissingHandlerClass");
    } finally {
        unlink($moduleDir.'/manifests/automation.manifest.json');
        rmdir($moduleDir.'/manifests');
        unlink($moduleDir.'/module.json');
        rmdir($moduleDir);
    }
});
