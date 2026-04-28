<?php

namespace Modules\CRMCore\AI\Tools;

use App\Models\Customer;
use Modules\CRMCore\Services\ClientPipelineService;

class ClientPipelineContextTool
{
    public function handle(Customer $client): array
    {
        return app(ClientPipelineService::class)->summary($client);
    }
}
