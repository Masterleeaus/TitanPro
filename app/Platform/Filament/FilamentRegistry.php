<?php

namespace App\Platform\Filament;

class FilamentRegistry
{
    /**
     * @var array<string, array{resources: array<int, string>, pages: array<int, string>, widgets: array<int, string>}>
     */
    private array $entries = [];

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $this->entries[$module] = [
            'resources' => $this->normalizeStrings((array) ($manifest['resources'] ?? [])),
            'pages' => $this->normalizeStrings((array) ($manifest['pages'] ?? [])),
            'widgets' => $this->normalizeStrings((array) ($manifest['widgets'] ?? [])),
        ];
    }

    /**
     * @return array<string, array{resources: array<int, string>, pages: array<int, string>, widgets: array<int, string>}>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, string>
     */
    public function resources(?string $module = null): array
    {
        return $this->entriesByType('resources', $module);
    }

    /**
     * @return array<int, string>
     */
    public function pages(?string $module = null): array
    {
        return $this->entriesByType('pages', $module);
    }

    /**
     * @return array<int, string>
     */
    public function widgets(?string $module = null): array
    {
        return $this->entriesByType('widgets', $module);
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array<int, string>
     */
    private function normalizeStrings(array $values): array
    {
        $normalized = array_values(array_filter($values, fn (mixed $value): bool => is_string($value) && $value !== ''));

        return array_values(array_unique($normalized));
    }

    /**
     * @return array<int, string>
     */
    private function entriesByType(string $type, ?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module][$type] ?? [];
        }

        $all = [];
        foreach ($this->entries as $entry) {
            $all = array_merge($all, $entry[$type] ?? []);
        }

        return array_values(array_unique($all));
    }
}
