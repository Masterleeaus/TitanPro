<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Http\Controllers\Dashboard;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorToolSetting;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowSetting;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowRun;
use App\Extensions\TitanOperator\System\Workflow\WorkflowRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TitanOperatorAutomationSettingsController
{
    public function __construct(protected WorkflowRegistry $registry = new WorkflowRegistry()) {}

    public function index(TitanOperator $titan_operator): View
    {
        // Workflows
        $allWorkflows = $this->registry->all();
        $wfSettings = TitanOperatorWorkflowSetting::query()
            ->where('operator_id', $titan_operator->getKey())
            ->get()
            ->keyBy('workflow_key');

        $workflowRows = [];
        foreach ($allWorkflows as $key => $wf) {
            $enabled = (bool)($wf['default_enabled'] ?? true);
            if ($wfSettings->has($key)) {
                $enabled = (bool) $wfSettings->get($key)->enabled;
            }
            $workflowRows[] = [
                'key' => (string) $key,
                'name' => (string)($wf['name'] ?? $key),
                'category' => (string)($wf['category'] ?? 'general'),
                'enabled' => $enabled,
                'requires_confirmation' => (bool)($wf['requires_confirmation'] ?? true),
            ];
        }

        // Tools
        $allTools = $this->registry->toolsAll();
        $toolSettings = TitanOperatorToolSetting::query()
            ->where('operator_id', $titan_operator->getKey())
            ->get()
            ->keyBy('tool_key');

        $toolRows = [];
        foreach ($allTools as $key => $tool) {
            $enabled = true;
            if ($toolSettings->has($key)) {
                $enabled = (bool) $toolSettings->get($key)->enabled;
            }
            $toolRows[] = [
                'key' => (string) $key,
                'name' => (string)($tool['name'] ?? $key),
                'category' => (string)($tool['category'] ?? 'general'),
                'enabled' => $enabled,
                'requires_confirmation' => (bool)($tool['requires_confirmation'] ?? true),
                'description' => (string)($tool['description'] ?? ''),
            ];
        }

        $runs = TitanOperatorWorkflowRun::query()
            ->where('operator_id', $titan_operator->getKey())
            ->orderByDesc('id')
            ->limit(25)
            ->get();

        return view('titan_operator::dashboard/automation/index', [
            'titan_operator' => $titan_operator,
            'workflowRows' => $workflowRows,
            'toolRows' => $toolRows,
            'runs' => $runs,
        ]);
    }

    public function update(Request $request, TitanOperator $titan_operator): RedirectResponse
    {
        // Update workflow enablement
        $enabledWorkflows = $this->normalizeEnabledKeys($request->input('workflows', []));
        foreach ($this->registry->all() as $key => $_wf) {
            $enabled = in_array((string) $key, $enabledWorkflows, true);
            TitanOperatorWorkflowSetting::query()->updateOrCreate(
                ['operator_id' => $titan_operator->getKey(), 'workflow_key' => (string) $key],
                ['enabled' => $enabled]
            );
        }

        // Update tool enablement
        $enabledTools = $this->normalizeEnabledKeys($request->input('tools', []));
        foreach ($this->registry->toolsAll() as $key => $_tool) {
            $enabled = in_array((string) $key, $enabledTools, true);
            TitanOperatorToolSetting::query()->updateOrCreate(
                ['operator_id' => $titan_operator->getKey(), 'tool_key' => (string) $key],
                ['enabled' => $enabled]
            );
        }

        // Gateway/tool runner settings (webhook-first)
        $titan_operator->external_endpoint_url = (string) $request->input('gateway.external_endpoint_url', $titan_operator->external_endpoint_url ?? '');
        $titan_operator->external_auth_type = (string) $request->input('gateway.external_auth_type', $titan_operator->external_auth_type ?? '');
        $titan_operator->external_auth_token = (string) $request->input('gateway.external_auth_token', $titan_operator->external_auth_token ?? '');
        $titan_operator->external_signing_secret = (string) $request->input('gateway.external_signing_secret', $titan_operator->external_signing_secret ?? '');
        $titan_operator->external_timeout_ms = (int) $request->input('gateway.external_timeout_ms', $titan_operator->external_timeout_ms ?? 15000);
        $titan_operator->limit_per_minute = (int) $request->input('gateway.limit_per_minute', $titan_operator->limit_per_minute ?? 60);
        $titan_operator->save();

        return back()->with('success', 'Automation settings updated.');
    }

    /** @param mixed $items @return array<int,string> */
    protected function normalizeEnabledKeys(mixed $items): array
    {
        $enabled = [];
        if (is_array($items)) {
            foreach ($items as $key => $val) {
                if ($val === '1' || $val === 1 || $val === true || $val === 'on') {
                    $enabled[] = (string) $key;
                }
            }
        }
        return $enabled;
    }
}
