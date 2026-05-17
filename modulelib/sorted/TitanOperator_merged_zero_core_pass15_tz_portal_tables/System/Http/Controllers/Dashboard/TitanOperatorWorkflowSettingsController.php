<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Http\Controllers\Dashboard;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowSetting;
use App\Extensions\TitanOperator\System\Workflow\WorkflowRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TitanOperatorWorkflowSettingsController
{
    public function __construct(protected WorkflowRegistry $registry = new WorkflowRegistry()) {}

    public function index(TitanOperator $titan_operator): View
    {
        $all = $this->registry->all();
        $settings = TitanOperatorWorkflowSetting::query()
            ->where('operator_id', $titan_operator->getKey())
            ->get()
            ->keyBy('workflow_key');

        $rows = [];
        foreach ($all as $key => $wf) {
            $enabled = (bool)($wf['default_enabled'] ?? true);
            if ($settings->has($key)) {
                $enabled = (bool)$settings->get($key)->enabled;
            }
            $rows[] = [
                'key' => $key,
                'name' => (string)($wf['name'] ?? $key),
                'category' => (string)($wf['category'] ?? 'general'),
                'enabled' => $enabled,
                'requires_confirmation' => (bool)($wf['requires_confirmation'] ?? true),
            ];
        }

        return view('titan_operator::dashboard.workflows.index', [
            'titan_operator' => $titan_operator,
            'rows' => $rows,
        ]);
    }

    public function update(Request $request, TitanOperator $titan_operator): RedirectResponse
    {
        $items = $request->input('enabled', []);
        $all = $this->registry->all();

        // Normalize to a set of enabled keys
        $enabledKeys = [];
        if (is_array($items)) {
            foreach ($items as $key => $val) {
                if ($val === '1' || $val === 1 || $val === true || $val === 'on') {
                    $enabledKeys[] = (string)$key;
                }
            }
        }

        foreach ($all as $key => $wf) {
            $enabled = in_array($key, $enabledKeys, true);
            TitanOperatorWorkflowSetting::query()->updateOrCreate(
                ['operator_id' => $titan_operator->getKey(), 'workflow_key' => $key],
                ['enabled' => $enabled]
            );
        }

        return back()->with('success', 'Workflow settings updated.');
    }
}
