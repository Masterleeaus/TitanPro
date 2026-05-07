<?php

namespace App\Platform\Modules;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ManifestLoader
{
    public function __construct(private readonly LoggerInterface $logger = new NullLogger()) {}

    public function load(string $modulePath): array
    {
        $manifests = [];
        $manifestsDir = rtrim($modulePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'manifests';

        foreach (glob($manifestsDir . DIRECTORY_SEPARATOR . '*.manifest.json') ?: [] as $manifestPath) {
            $decoded = json_decode((string) file_get_contents($manifestPath), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->warning('Classic manifest file is invalid JSON and was skipped.', ['path' => $manifestPath]);

                continue;
            }

            $key = basename($manifestPath, '.manifest.json');
            $manifests[$key] = $decoded;
        }

        return $manifests;
    }
}
