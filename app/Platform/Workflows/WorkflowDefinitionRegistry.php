<?php

namespace App\Platform\Workflows;

class WorkflowDefinitionRegistry
{
    /**
     * @var array<string, array<int, array{module: string, key: string, class: string}>>
     */
    private array $entries = [];

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $definitions = [];
        foreach ((array) ($manifest['workflows'] ?? []) as $workflow) {
            if (is_string($workflow) && $workflow !== '') {
                $definitions[] = [
                    'module' => $module,
                    'key' => $workflow,
                    'class' => $workflow,
                ];
                continue;
            }

            if (! is_array($workflow)) {
                continue;
            }

            $key = $workflow['key'] ?? $workflow['id'] ?? $workflow['name'] ?? $workflow['class'] ?? null;
            if (! is_string($key) || $key === '') {
                continue;
            }

            $class = $workflow['class'] ?? $key;

            if (! is_string($class) || $class === '') {
                continue;
            }

            $definitions[] = [
                'module' => $module,
                'key' => $key,
                'class' => $class,
            ];
        }

        $this->entries[$module] = $definitions;
    }

    /**
     * @return array<string, array<int, array{module: string, key: string, class: string}>>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, array{module: string, key: string, class: string}>
     */
    public function byModule(string $module): array
    {
        return $this->entries[$module] ?? [];
    }

    /**
     * @return array{module: string, key: string, class: string}|null
     */
    public function find(string $key): ?array
    {
        foreach ($this->entries as $moduleEntries) {
            foreach ($moduleEntries as $entry) {
                if ($entry['key'] === $key || $entry['class'] === $key) {
                    return $entry;
                }
            }
        }

        return null;
    }
}
