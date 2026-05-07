<?php

namespace App\Platform\Modules;

class ModuleDeclarationRegistry
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private array $manifests = [];

    /**
     * @var array<string, array<int, array{module: string, type: string, key: string, data: mixed}>>
     */
    private array $entries = [];

    /**
     * @param  array<string, string>  $declarationKeys
     */
    public function __construct(
        private readonly array $declarationKeys,
    ) {}

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $this->manifests[$module] = $manifest;
        $normalized = [];

        foreach ($this->declarationKeys as $manifestKey => $type) {
            foreach ($this->normalizeDeclarationValues($manifest[$manifestKey] ?? null) as $index => $declaration) {
                $entry = $this->normalizeEntry($module, $type, $declaration, $index);

                if ($entry !== null) {
                    $normalized[] = $entry;
                }
            }
        }

        $this->entries[$module] = $normalized;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function manifests(): array
    {
        return $this->manifests;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function manifest(string $module): ?array
    {
        return $this->manifests[$module] ?? null;
    }

    /**
     * @return array<string, array<int, array{module: string, type: string, key: string, data: mixed}>>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, array{module: string, type: string, key: string, data: mixed}>
     */
    public function byModule(string $module): array
    {
        return $this->entries[$module] ?? [];
    }

    /**
     * @return array<int, array{module: string, type: string, key: string, data: mixed}>
     */
    public function byType(string $type, ?string $module = null): array
    {
        $entries = $module === null
            ? array_merge(...array_values($this->entries) ?: [[]])
            : ($this->entries[$module] ?? []);

        return array_values(array_filter(
            $entries,
            fn (array $entry): bool => $entry['type'] === $type
        ));
    }

    /**
     * @return array{module: string, type: string, key: string, data: mixed}|null
     */
    public function find(string $module, string $key): ?array
    {
        foreach ($this->entries[$module] ?? [] as $entry) {
            if ($entry['key'] === $key) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * @return array<int, mixed>
     */
    private function normalizeDeclarationValues(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        if (! is_array($value)) {
            return [$value];
        }

        if ($value === []) {
            return [];
        }

        return array_is_list($value) ? $value : [$value];
    }

    /**
     * @return array{module: string, type: string, key: string, data: mixed}|null
     */
    private function normalizeEntry(string $module, string $type, mixed $declaration, int $index): ?array
    {
        if (is_string($declaration)) {
            if ($declaration === '') {
                return null;
            }

            return [
                'module' => $module,
                'type' => $type,
                'key' => $declaration,
                'data' => $declaration,
            ];
        }

        if (! is_array($declaration)) {
            return null;
        }

        $key = $this->declarationKey($declaration) ?? $type.'.'.$index;

        return [
            'module' => $module,
            'type' => $type,
            'key' => $key,
            'data' => $declaration,
        ];
    }

    /**
     * @param  array<string, mixed>  $declaration
     */
    private function declarationKey(array $declaration): ?string
    {
        foreach (['key', 'id', 'name', 'component', 'widget', 'layout', 'table', 'setting', 'route', 'action', 'class', 'label'] as $candidate) {
            $value = $declaration[$candidate] ?? null;

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
