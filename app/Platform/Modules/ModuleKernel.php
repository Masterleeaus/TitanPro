<?php

namespace App\Platform\Modules;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ModuleKernel
{
    public function __construct(
        private readonly ModuleMetadataReader $metadataReader,
        private readonly ManifestLoader $manifestLoader,
        private readonly BlueprintManifestLoader $blueprintManifestLoader,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {}

    /**
     * @param  array<int, string>|string  $paths
     * @return array<string, array<string, mixed>>
     */
    public function discover(array|string $paths): array
    {
        $modules = [];

        foreach ($this->normalizePaths($paths) as $basePath) {
            foreach (glob($basePath . DIRECTORY_SEPARATOR . '*/module.json') ?: [] as $metadataPath) {
                $modulePath = dirname($metadataPath);
                $metadata = $this->metadataReader->read($modulePath);

                if ($metadata === null) {
                    continue;
                }

                $manifestPathMap = $metadata['manifest'] ?? [];
                if (! is_array($manifestPathMap)) {
                    $this->logger->warning('Unknown manifest format. Module manifest path-map skipped.', [
                        'module_path' => $modulePath,
                    ]);
                    $manifestPathMap = [];
                }

                $classicManifests = $this->manifestLoader->load($modulePath);
                $blueprintManifests = $this->blueprintManifestLoader->load($modulePath, $manifestPathMap);

                $moduleName = is_string($metadata['name'] ?? null) && $metadata['name'] !== ''
                    ? $metadata['name']
                    : basename($modulePath);

                $metadata['manifest_paths'] = $manifestPathMap;
                $metadata['manifest'] = array_replace($classicManifests, $blueprintManifests);
                $metadata['path'] = $modulePath;

                $modules[$moduleName] = $metadata;
            }
        }

        ksort($modules);

        return $modules;
    }

    /**
     * @param  array<int, string>|string  $paths
     * @return array<int, string>
     */
    private function normalizePaths(array|string $paths): array
    {
        $paths = is_array($paths) ? $paths : [$paths];

        $normalized = [];

        foreach ($paths as $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }

            $absolutePath = str_starts_with($path, DIRECTORY_SEPARATOR) ? $path : base_path($path);

            if (! is_dir($absolutePath)) {
                $this->logger->warning('Configured module discovery path does not exist and was skipped.', ['path' => $absolutePath]);
                continue;
            }

            $normalized[] = $absolutePath;
        }

        return array_values(array_unique($normalized));
    }
}
