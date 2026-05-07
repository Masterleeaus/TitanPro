<?php

namespace App\Platform\Modules;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class BlueprintManifestLoader
{
    public function __construct(private readonly LoggerInterface $logger = new NullLogger()) {}

    public function load(string $modulePath, array $manifestPathMap): array
    {
        $loaded = [];

        foreach ($manifestPathMap as $section => $relativePath) {
            if (! is_string($section) || ! is_string($relativePath) || $relativePath === '') {
                $this->logger->warning('Unknown blueprint manifest format. Entry skipped.', ['module_path' => $modulePath, 'section' => $section]);

                continue;
            }

            if (! str_ends_with(strtolower($relativePath), '.json')) {
                $this->logger->warning('Unknown blueprint manifest format. Entry skipped.', [
                    'module_path' => $modulePath,
                    'section' => $section,
                    'path' => $relativePath,
                ]);

                continue;
            }

            $manifestPath = rtrim($modulePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($relativePath, DIRECTORY_SEPARATOR);

            if (! is_file($manifestPath)) {
                $this->logger->warning('Blueprint manifest file is missing and was skipped.', ['path' => $manifestPath, 'section' => $section]);

                continue;
            }

            $decoded = json_decode((string) file_get_contents($manifestPath), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->warning('Blueprint manifest file is invalid JSON and was skipped.', ['path' => $manifestPath, 'section' => $section]);

                continue;
            }

            $loaded[$section] = $decoded;
        }

        return $loaded;
    }
}
