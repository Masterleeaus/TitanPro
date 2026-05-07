<?php

/**
 * Verifies that `modules:doctor` surfaces missing agent and tool classes
 * declared in AI manifests (`manifests/ai.manifest.json` and individual
 * agent manifests under `Agents/`).
 */

test('modules:doctor surfaces missing agent classes declared in ai.manifest.json', function () {
    $modulesBase = base_path(config('titan-modules.path', 'Modules'));

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $moduleName = '_DoctorAIMissingAgent_'.uniqid();
    $moduleDir  = $modulesBase.'/'.$moduleName;
    mkdir($moduleDir.'/manifests', 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name'   => $moduleName,
        'active' => 1,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    file_put_contents($moduleDir.'/manifests/ai.manifest.json', json_encode([
        'enabled' => true,
        'agents'  => [
            "Modules\\{$moduleName}\\AI\\Agents\\MissingAgentClass",
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
        $this->artisan('modules:doctor', ['--skip-schema' => true])
            ->expectsOutputToContain("missing agent class Modules\\{$moduleName}\\AI\\Agents\\MissingAgentClass");
    } finally {
        unlink($moduleDir.'/manifests/ai.manifest.json');
        rmdir($moduleDir.'/manifests');
        unlink($moduleDir.'/module.json');
        rmdir($moduleDir);
    }
});

test('modules:doctor surfaces missing tool classes declared in ai.manifest.json', function () {
    $modulesBase = base_path(config('titan-modules.path', 'Modules'));

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $moduleName = '_DoctorAIMissingTool_'.uniqid();
    $moduleDir  = $modulesBase.'/'.$moduleName;
    mkdir($moduleDir.'/manifests', 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name'   => $moduleName,
        'active' => 1,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    file_put_contents($moduleDir.'/manifests/ai.manifest.json', json_encode([
        'enabled' => true,
        'tools'   => [
            ['name' => 'create_booking', 'class' => "Modules\\{$moduleName}\\AI\\Tools\\MissingToolClass"],
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
        $this->artisan('modules:doctor', ['--skip-schema' => true])
            ->expectsOutputToContain("missing tool class Modules\\{$moduleName}\\AI\\Tools\\MissingToolClass");
    } finally {
        unlink($moduleDir.'/manifests/ai.manifest.json');
        rmdir($moduleDir.'/manifests');
        unlink($moduleDir.'/module.json');
        rmdir($moduleDir);
    }
});

test('modules:doctor surfaces missing agent_class declared in agent manifest files', function () {
    $modulesBase = base_path(config('titan-modules.path', 'Modules'));

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $moduleName = '_DoctorAIMissingAgentManifest_'.uniqid();
    $moduleDir  = $modulesBase.'/'.$moduleName;
    mkdir($moduleDir.'/Agents/BookingAgent', 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name'   => $moduleName,
        'active' => 1,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    file_put_contents($moduleDir.'/Agents/BookingAgent/agent.manifest.json', json_encode([
        'agent_id'    => 'booking-agent',
        'agent_class' => "Modules\\{$moduleName}\\AI\\Agents\\MissingBookingAgent",
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
        $this->artisan('modules:doctor', ['--skip-schema' => true])
            ->expectsOutputToContain("missing agent class Modules\\{$moduleName}\\AI\\Agents\\MissingBookingAgent");
    } finally {
        unlink($moduleDir.'/Agents/BookingAgent/agent.manifest.json');
        rmdir($moduleDir.'/Agents/BookingAgent');
        rmdir($moduleDir.'/Agents');
        unlink($moduleDir.'/module.json');
        rmdir($moduleDir);
    }
});

test('modules:doctor does not flag AI manifest entries for disabled modules', function () {
    $modulesBase = base_path(config('titan-modules.path', 'Modules'));

    if (! is_dir($modulesBase)) {
        $this->markTestSkipped('Modules directory not found.');
    }

    $moduleName = '_DoctorAIDisabled_'.uniqid();
    $moduleDir  = $modulesBase.'/'.$moduleName;
    mkdir($moduleDir.'/manifests', 0755, true);

    file_put_contents($moduleDir.'/module.json', json_encode([
        'name'   => $moduleName,
        'active' => 0,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    file_put_contents($moduleDir.'/manifests/ai.manifest.json', json_encode([
        'agents' => ["Modules\\{$moduleName}\\AI\\Agents\\NonExistentAgent"],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    try {
        $this->artisan('modules:doctor', ['--skip-schema' => true])
            ->doesntExpectOutputToContain("Modules\\{$moduleName}\\AI\\Agents\\NonExistentAgent");
    } finally {
        unlink($moduleDir.'/manifests/ai.manifest.json');
        rmdir($moduleDir.'/manifests');
        unlink($moduleDir.'/module.json');
        rmdir($moduleDir);
    }
});
