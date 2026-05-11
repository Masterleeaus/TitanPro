<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Workflow;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorToolSetting;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowSetting;
use Illuminate\Support\Arr;

class WorkflowRegistry
{
    /** @return array<string, mixed> */
    public function all(): array
    {
        return config('titan_operator.workflows.workflows', []);
    }

    /** @return array<string, mixed>|null */
    public function get(string $workflowKey): ?array
    {
        $all = $this->all();
        return $all[$workflowKey] ?? null;
    }

    public function exists(string $workflowKey): bool
    {
        return $this->get($workflowKey) !== null;
    }

    /**
     * Enabled workflows for a titan_operator.
     * If no explicit settings exist, falls back to each workflow's default_enabled.
     *
     * @return array<int, array<string, mixed>>
     */
    public function enabledFor(TitanOperator $titan_operator): array
    {
        $all = $this->all();

        $settings = TitanOperatorWorkflowSetting::query()
            ->where('operator_id', $titan_operator->getKey())
            ->get()
            ->keyBy('workflow_key');

        $enabled = [];
        foreach ($all as $key => $wf) {
            $isEnabled = Arr::get($wf, 'default_enabled', true);
            if ($settings->has($key)) {
                $isEnabled = (bool) $settings->get($key)->enabled;
            }
            if ($isEnabled) {
                $wf['workflow_key'] = $key;
                $enabled[] = $wf;
            }
        }

        return $enabled;
    }

    /** @return array<int, string> */
    public function toolsAllowlist(): array
    {
        return config('titan_operator.workflows.tools', []);
    }

    /** @return array<string, mixed> */
    public function toolsAll(): array
    {
        return config('titan_operator.tools.tools', []);
    }

    /** @return array<string, mixed>|null */
    public function tool(string $toolKey): ?array
    {
        $all = $this->toolsAll();
        return $all[$toolKey] ?? null;
    }

    /**
     * Enabled tools for a titan_operator.
     * If no explicit settings exist, tools default to enabled.
     *
     * @return array<int, array<string, mixed>>
     */
    public function toolsEnabledFor(TitanOperator $titan_operator): array
    {
        $all = $this->toolsAll();

        $settings = TitanOperatorToolSetting::query()
            ->where('operator_id', $titan_operator->getKey())
            ->get()
            ->keyBy('tool_key');

        $enabled = [];
        foreach ($all as $key => $tool) {
            $isEnabled = true;
            if ($settings->has($key)) {
                $isEnabled = (bool) $settings->get($key)->enabled;
            }
            if ($isEnabled) {
                $tool['tool_key'] = $key;
                $enabled[] = $tool;
            }
        }
        return $enabled;
    }

    public function toolIsEnabledFor(TitanOperator $titan_operator, string $toolKey): bool
    {
        $setting = TitanOperatorToolSetting::query()
            ->where('operator_id', $titan_operator->getKey())
            ->where('tool_key', $toolKey)
            ->first();

        if ($setting) {
            return (bool) $setting->enabled;
        }

        // default enabled
        return $this->tool($toolKey) !== null;
    }
}
