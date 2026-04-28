<?php

namespace Modules\CRMCore\AI\Agents;

class CRMCoreAgent
{
    public function capabilities(): array
    {
        return ['client_pipeline_context', 'deal_project_advisor', 'original_crmcore_context'];
    }
}
