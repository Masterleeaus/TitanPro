<?php

namespace Modules\CRMCore\AI\Tools;

use Modules\CRMCore\Models\Deal;

class DealProjectAdvisorTool
{
    public function handle(Deal $deal): array
    {
        return [
            'deal_id' => $deal->getKey(),
            'has_client' => filled($deal->client_id ?? null),
            'has_project' => filled($deal->crmcore_project_id ?? null),
            'service_interest' => $deal->crmcore_service_interest ?? null,
            'recommendation' => filled($deal->crmcore_project_id ?? null)
                ? 'Already converted to project.'
                : 'Confirm scope, client, deadline, and service match before converting.',
        ];
    }
}
