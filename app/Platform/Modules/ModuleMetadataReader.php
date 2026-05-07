<?php

namespace App\Platform\Modules;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ModuleMetadataReader
{
    public function __construct(private readonly LoggerInterface $logger = new NullLogger()) {}

    public function read(string $modulePath): ?array
    {
        $metadataPath = rtrim($modulePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'module.json';

        if (! is_file($metadataPath)) {
            $this->logger->warning('Module metadata file is missing.', ['path' => $metadataPath]);

            return null;
        }

        $decoded = json_decode((string) file_get_contents($metadataPath), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            $this->logger->warning('Module metadata file is invalid JSON.', ['path' => $metadataPath]);

            return null;
        }

        return $decoded;
    }
}
