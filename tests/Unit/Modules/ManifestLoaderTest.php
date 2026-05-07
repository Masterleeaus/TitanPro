<?php

use App\Platform\Modules\ManifestLoader;
use Psr\Log\AbstractLogger;

class TestManifestLoaderLogger extends AbstractLogger
{
    public array $warnings = [];

    public function log($level, $message, array $context = []): void
    {
        if ($level === 'warning') {
            $this->warnings[] = ['message' => (string) $message, 'context' => $context];
        }
    }
}

test('classic manifest loader loads manifests slash star manifest json files', function () {
    $modulePath = sys_get_temp_dir() . '/titan_manifest_loader_' . uniqid('', true);
    mkdir($modulePath . '/manifests', 0777, true);

    file_put_contents($modulePath . '/manifests/workflows.manifest.json', json_encode(['steps' => ['a', 'b']]));
    file_put_contents($modulePath . '/manifests/api.manifest.json', json_encode(['routes' => ['/v1/status']]));

    $loader = new ManifestLoader();
    $loaded = $loader->load($modulePath);

    expect($loaded)->toHaveKeys(['workflows', 'api']);
    expect($loaded['workflows']['steps'])->toBe(['a', 'b']);
    expect($loaded['api']['routes'])->toBe(['/v1/status']);

    unlink($modulePath . '/manifests/workflows.manifest.json');
    unlink($modulePath . '/manifests/api.manifest.json');
    rmdir($modulePath . '/manifests');
    rmdir($modulePath);
});

test('classic manifest loader skips invalid json and logs warning', function () {
    $modulePath = sys_get_temp_dir() . '/titan_manifest_loader_invalid_' . uniqid('', true);
    mkdir($modulePath . '/manifests', 0777, true);
    file_put_contents($modulePath . '/manifests/bad.manifest.json', '{bad}');

    $logger = new TestManifestLoaderLogger();
    $loader = new ManifestLoader($logger);

    expect($loader->load($modulePath))->toBe([]);
    expect($logger->warnings)->not->toBeEmpty();

    unlink($modulePath . '/manifests/bad.manifest.json');
    rmdir($modulePath . '/manifests');
    rmdir($modulePath);
});
