<?php

use App\Platform\Modules\ModuleMetadataReader;
use Psr\Log\AbstractLogger;

class TestArrayLogger extends AbstractLogger
{
    public array $warnings = [];

    public function log($level, $message, array $context = []): void
    {
        if ($level === 'warning') {
            $this->warnings[] = ['message' => (string) $message, 'context' => $context];
        }
    }
}

test('module metadata reader parses name version description and declared sections', function () {
    $modulePath = sys_get_temp_dir() . '/titan_metadata_' . uniqid('', true);
    mkdir($modulePath, 0777, true);

    file_put_contents($modulePath . '/module.json', json_encode([
        'name' => 'Catalog',
        'version' => '1.2.3',
        'description' => 'Catalog module',
        'providers' => ['Modules\\Catalog\\Providers\\CatalogServiceProvider'],
        'manifest' => ['ai' => 'manifests/ai.json'],
        'custom_section' => ['enabled' => true],
    ]));

    $reader = new ModuleMetadataReader();
    $metadata = $reader->read($modulePath);

    expect($metadata)->not->toBeNull();
    expect($metadata['name'])->toBe('Catalog');
    expect($metadata['version'])->toBe('1.2.3');
    expect($metadata['description'])->toBe('Catalog module');
    expect($metadata['providers'])->toBeArray();
    expect($metadata['manifest'])->toHaveKey('ai');
    expect($metadata)->toHaveKey('custom_section');

    unlink($modulePath . '/module.json');
    rmdir($modulePath);
});

test('module metadata reader skips invalid module json and logs warning', function () {
    $modulePath = sys_get_temp_dir() . '/titan_metadata_invalid_' . uniqid('', true);
    mkdir($modulePath, 0777, true);
    file_put_contents($modulePath . '/module.json', '{invalid json');

    $logger = new TestArrayLogger();
    $reader = new ModuleMetadataReader($logger);

    expect($reader->read($modulePath))->toBeNull();
    expect($logger->warnings)->not->toBeEmpty();

    unlink($modulePath . '/module.json');
    rmdir($modulePath);
});
