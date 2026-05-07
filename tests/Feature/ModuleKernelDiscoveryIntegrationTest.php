<?php

use App\Platform\Modules\BlueprintManifestLoader;
use App\Platform\Modules\ManifestLoader;
use App\Platform\Modules\ModuleKernel;
use App\Platform\Modules\ModuleMetadataReader;
use Psr\Log\AbstractLogger;

class TestModuleKernelLogger extends AbstractLogger
{
    public array $warnings = [];

    public function log($level, $message, array $context = []): void
    {
        if ($level === 'warning') {
            $this->warnings[] = ['message' => (string) $message, 'context' => $context];
        }
    }
}

test('module kernel discovers modules and merges classic and blueprint manifests', function () {
    $modulesBase = sys_get_temp_dir() . '/titan_kernel_' . uniqid('', true);

    mkdir($modulesBase . '/Ops/manifests', 0777, true);
    mkdir($modulesBase . '/Docs/manifests', 0777, true);
    mkdir($modulesBase . '/Broken', 0777, true);

    file_put_contents($modulesBase . '/Ops/module.json', json_encode([
        'name' => 'Ops',
        'version' => '1.0.0',
        'description' => 'Operations module',
        'manifest' => [
            'ai' => 'manifests/ai.json',
        ],
    ]));
    file_put_contents($modulesBase . '/Ops/manifests/ai.json', json_encode(['tools' => ['dispatch']]));
    file_put_contents($modulesBase . '/Ops/manifests/workflows.manifest.json', json_encode(['states' => ['queued', 'done']]));

    file_put_contents($modulesBase . '/Docs/module.json', json_encode([
        'name' => 'Docs',
        'version' => '2.0.0',
        'description' => 'Documentation module',
    ]));
    file_put_contents($modulesBase . '/Docs/manifests/api.manifest.json', json_encode(['routes' => ['/docs']]));

    file_put_contents($modulesBase . '/Broken/module.json', json_encode([
        'name' => 'Broken',
        'manifest' => 'unknown-format',
    ]));

    $logger = new TestModuleKernelLogger();
    $kernel = new ModuleKernel(
        new ModuleMetadataReader($logger),
        new ManifestLoader($logger),
        new BlueprintManifestLoader($logger),
        $logger,
    );

    $discovered = $kernel->discover($modulesBase);

    expect($discovered)->toHaveKeys(['Ops', 'Docs', 'Broken']);

    expect($discovered['Ops']['manifest'])->toHaveKeys(['ai', 'workflows']);
    expect($discovered['Ops']['manifest']['ai']['tools'])->toContain('dispatch');
    expect($discovered['Ops']['manifest']['workflows']['states'])->toContain('queued');

    expect($discovered['Docs']['manifest'])->toHaveKey('api');
    expect($discovered['Docs']['manifest']['api']['routes'])->toContain('/docs');

    expect($discovered['Broken']['manifest'])->toBe([]);
    expect($logger->warnings)->not->toBeEmpty();

    unlink($modulesBase . '/Ops/manifests/ai.json');
    unlink($modulesBase . '/Ops/manifests/workflows.manifest.json');
    unlink($modulesBase . '/Ops/module.json');
    unlink($modulesBase . '/Docs/manifests/api.manifest.json');
    unlink($modulesBase . '/Docs/module.json');
    unlink($modulesBase . '/Broken/module.json');

    rmdir($modulesBase . '/Ops/manifests');
    rmdir($modulesBase . '/Ops');
    rmdir($modulesBase . '/Docs/manifests');
    rmdir($modulesBase . '/Docs');
    rmdir($modulesBase . '/Broken');
    rmdir($modulesBase);
});
