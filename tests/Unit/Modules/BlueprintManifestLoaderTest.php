<?php

use App\Platform\Modules\BlueprintManifestLoader;
use Psr\Log\AbstractLogger;

class TestBlueprintManifestLogger extends AbstractLogger
{
    public array $warnings = [];

    public function log($level, $message, array $context = []): void
    {
        if ($level === 'warning') {
            $this->warnings[] = ['message' => (string) $message, 'context' => $context];
        }
    }
}

test('blueprint manifest loader resolves module json manifest path map', function () {
    $modulePath = sys_get_temp_dir() . '/titan_blueprint_loader_' . uniqid('', true);
    mkdir($modulePath . '/AI/Guardrails', 0777, true);
    mkdir($modulePath . '/manifests', 0777, true);

    file_put_contents($modulePath . '/AI/Guardrails/guardrails.json', json_encode(['rules' => ['pii_redaction']]));
    file_put_contents($modulePath . '/manifests/ai.json', json_encode(['tools' => ['lookup']]));

    $loader = new BlueprintManifestLoader();
    $loaded = $loader->load($modulePath, [
        'guardrails' => 'AI/Guardrails/guardrails.json',
        'ai' => 'manifests/ai.json',
    ]);

    expect($loaded)->toHaveKeys(['guardrails', 'ai']);
    expect($loaded['guardrails']['rules'])->toContain('pii_redaction');
    expect($loaded['ai']['tools'])->toContain('lookup');

    unlink($modulePath . '/AI/Guardrails/guardrails.json');
    unlink($modulePath . '/manifests/ai.json');
    rmdir($modulePath . '/AI/Guardrails');
    rmdir($modulePath . '/AI');
    rmdir($modulePath . '/manifests');
    rmdir($modulePath);
});

test('blueprint manifest loader skips unknown manifest format and logs warning', function () {
    $modulePath = sys_get_temp_dir() . '/titan_blueprint_loader_unknown_' . uniqid('', true);
    mkdir($modulePath, 0777, true);

    $logger = new TestBlueprintManifestLogger();
    $loader = new BlueprintManifestLoader($logger);

    $loaded = $loader->load($modulePath, ['notes' => 'README.md']);

    expect($loaded)->toBe([]);
    expect($logger->warnings)->not->toBeEmpty();

    rmdir($modulePath);
});
