<?php

namespace App\Platform\AI;

/**
 * Registry for module-level AI manifests loaded from `manifests/ai.manifest.json`.
 *
 * Each module may declare:
 *  - agents      array of agent class names
 *  - tools       array of tool class names or structured tool entries
 *  - prompts     keyed map of prompt template references (key => path/string)
 *  - memory      array of memory class names or driver references
 *  - catalog     flexible catalog declarations linked to the module
 */
class AIManifestRegistry
{
    /**
     * @var array<string, array{agents: array<int, string>, tools: array<int, array<string, mixed>>, prompts: array<string, mixed>, memory: array<int, string>, catalog: array<int, mixed>}>
     */
    private array $entries = [];

    /**
     * Register the AI manifest for a single module.
     *
     * Re-registering the same module replaces existing entries, making the
     * operation idempotent.
     *
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $this->entries[$module] = [
            'agents'  => $this->normalizeStrings((array) ($manifest['agents'] ?? [])),
            'tools'   => $this->normalizeTools((array) ($manifest['tools'] ?? [])),
            'prompts' => $this->normalizePrompts($manifest['prompts'] ?? []),
            'memory'  => $this->normalizeStrings(array_merge(
                (array) ($manifest['memory'] ?? []),
                (array) ($manifest['memory_drivers'] ?? []),
            )),
            'catalog' => array_values((array) ($manifest['catalog'] ?? [])),
        ];
    }

    /**
     * @return array<string, array{agents: array<int, string>, tools: array<int, array<string, mixed>>, prompts: array<string, mixed>, memory: array<int, string>, catalog: array<int, mixed>}>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, string>
     */
    public function agents(?string $module = null): array
    {
        return $this->stringEntriesByType('agents', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function tools(?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module]['tools'] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry['tools'] ?? []);
        }

        return $combined;
    }

    /**
     * Return all prompt templates across modules, or for a specific module.
     *
     * @return array<string, mixed>
     */
    public function prompts(?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module]['prompts'] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry['prompts'] ?? []);
        }

        return $combined;
    }

    /**
     * Retrieve a single prompt template by key, optionally scoped to a module.
     */
    public function promptByKey(string $key, ?string $module = null): mixed
    {
        if ($module !== null) {
            return $this->entries[$module]['prompts'][$key] ?? null;
        }

        foreach ($this->entries as $entry) {
            if (isset($entry['prompts'][$key])) {
                return $entry['prompts'][$key];
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    public function memory(?string $module = null): array
    {
        return $this->stringEntriesByType('memory', $module);
    }

    /**
     * @return array<int, mixed>
     */
    public function catalog(?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module]['catalog'] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry['catalog'] ?? []);
        }

        return $combined;
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array<int, string>
     */
    private function normalizeStrings(array $values): array
    {
        $normalized = array_values(array_filter($values, fn (mixed $v): bool => is_string($v) && $v !== ''));

        return array_values(array_unique($normalized));
    }

    /**
     * Normalize tool entries: strings become minimal arrays; arrays are passed through.
     *
     * @param  array<int, mixed>  $tools
     * @return array<int, array<string, mixed>>
     */
    private function normalizeTools(array $tools): array
    {
        $normalized = [];

        foreach ($tools as $tool) {
            if (is_string($tool) && $tool !== '') {
                $normalized[] = ['name' => $tool, 'class' => $tool];
                continue;
            }

            if (is_array($tool)) {
                $name = $tool['name'] ?? $tool['class'] ?? $tool['key'] ?? null;
                if (is_string($name) && $name !== '') {
                    $normalized[] = array_merge(['name' => $name], $tool);
                }
            }
        }

        return $normalized;
    }

    /**
     * Normalize prompts: arrays are returned as-is; strings are wrapped under 'default'.
     *
     * @param  mixed  $prompts
     * @return array<string, mixed>
     */
    private function normalizePrompts(mixed $prompts): array
    {
        if (is_array($prompts)) {
            return $prompts;
        }

        if (is_string($prompts) && $prompts !== '') {
            return ['default' => $prompts];
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    private function stringEntriesByType(string $type, ?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module][$type] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry[$type] ?? []);
        }

        return array_values(array_unique($combined));
    }
}
