<?php

namespace App\Platform\AI;

/**
 * Registry for blueprint-level AI manifests loaded from a module's `AI/` directory
 * and `Agents/` directory.
 *
 * Each module may expose:
 *  - agents    parsed agent.manifest.json entries from Agents/ subdirectories
 *  - indexing  content of AI/Indexing/indexing.manifest.json
 *  - retrieval content of AI/Retrieval/retrieval.policy.json
 *  - citations content of AI/Citations/citation.schema.json
 *  - guardrails content of AI/Guardrails/guardrails.json
 *  - actions   content of AI/Actions/action-map.json
 *  - telemetry content of AI/Telemetry/telemetry.manifest.json
 *  - control   content of AI/Control/control.manifest.json
 *
 * These are loaded and stored separately from the classic module-level AI manifest.
 */
class BlueprintAIManifestRegistry
{
    /**
     * @var array<string, array{agents: array<int, array<string, mixed>>, indexing: array<string, mixed>|null, retrieval: array<string, mixed>|null, citations: array<string, mixed>|null, guardrails: array<string, mixed>|null, actions: array<string, mixed>|null, telemetry: array<string, mixed>|null, control: array<string, mixed>|null}>
     */
    private array $entries = [];

    /**
     * Register blueprint AI declarations for a single module.
     *
     * Re-registering the same module replaces existing entries, making the
     * operation idempotent.
     *
     * @param  array<string, mixed>  $blueprint  Aggregated blueprint data keyed by section name.
     */
    public function registerManifest(string $module, array $blueprint): void
    {
        $this->entries[$module] = [
            'agents'     => $this->normalizeAgents((array) ($blueprint['agents'] ?? [])),
            'indexing'   => is_array($blueprint['indexing'] ?? null) ? $blueprint['indexing'] : null,
            'retrieval'  => is_array($blueprint['retrieval'] ?? null) ? $blueprint['retrieval'] : null,
            'citations'  => is_array($blueprint['citations'] ?? null) ? $blueprint['citations'] : null,
            'guardrails' => is_array($blueprint['guardrails'] ?? null) ? $blueprint['guardrails'] : null,
            'actions'    => is_array($blueprint['actions'] ?? null) ? $blueprint['actions'] : null,
            'telemetry'  => is_array($blueprint['telemetry'] ?? null) ? $blueprint['telemetry'] : null,
            'control'    => is_array($blueprint['control'] ?? null) ? $blueprint['control'] : null,
        ];
    }

    /**
     * @return array<string, array{agents: array<int, array<string, mixed>>, indexing: array<string, mixed>|null, retrieval: array<string, mixed>|null, citations: array<string, mixed>|null, guardrails: array<string, mixed>|null, actions: array<string, mixed>|null, telemetry: array<string, mixed>|null, control: array<string, mixed>|null}>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * Return parsed agent manifests for a module or all modules.
     *
     * @return array<int, array<string, mixed>>
     */
    public function agents(?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module]['agents'] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry['agents'] ?? []);
        }

        return $combined;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function indexing(string $module): ?array
    {
        return $this->entries[$module]['indexing'] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function retrieval(string $module): ?array
    {
        return $this->entries[$module]['retrieval'] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function citations(string $module): ?array
    {
        return $this->entries[$module]['citations'] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function guardrails(string $module): ?array
    {
        return $this->entries[$module]['guardrails'] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function actions(string $module): ?array
    {
        return $this->entries[$module]['actions'] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function telemetry(string $module): ?array
    {
        return $this->entries[$module]['telemetry'] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function control(string $module): ?array
    {
        return $this->entries[$module]['control'] ?? null;
    }

    /**
     * Normalize agent entries to a consistent array-of-arrays shape.
     *
     * @param  array<int, mixed>  $agents
     * @return array<int, array<string, mixed>>
     */
    private function normalizeAgents(array $agents): array
    {
        $normalized = [];

        foreach ($agents as $agent) {
            if (is_array($agent) && ! empty($agent)) {
                $normalized[] = $agent;
            } elseif (is_string($agent) && $agent !== '') {
                $normalized[] = ['agent_id' => $agent];
            }
        }

        return $normalized;
    }
}
