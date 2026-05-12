<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CRMCore\Workflows\Definitions\DealToProjectWorkflow;

class WorkflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (! class_exists(\Workflow\WorkflowRegistry::class)) {
            return;
        }

        foreach ($this->workflowClasses() as $workflowClass) {
            if (class_exists($workflowClass)) {
                \Workflow\WorkflowRegistry::register($workflowClass);
            }
        }
    }

    /**
     * @return array<int, class-string>
     */
    private function workflowClasses(): array
    {
        $manifestPath = __DIR__ . '/../manifests/workflows.manifest.json';
        if (! file_exists($manifestPath)) {
            return [DealToProjectWorkflow::class];
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        if (! is_array($manifest)) {
            return [DealToProjectWorkflow::class];
        }

        $workflows = $manifest['workflows'] ?? [];
        if (! is_array($workflows) || $workflows === []) {
            return [DealToProjectWorkflow::class];
        }

        return array_values(array_filter(array_map(function ($workflow): ?string {
            if (! is_string($workflow) || $workflow === '') {
                return null;
            }

            if (str_contains($workflow, '\\')) {
                return $workflow;
            }

            return 'Modules\\CRMCore\\Workflows\\Definitions\\' . $workflow;
        }, $workflows)));
    }
}
