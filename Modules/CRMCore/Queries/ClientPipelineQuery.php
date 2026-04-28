<?php

namespace Modules\CRMCore\Queries;

use App\Models\Customer;
use Modules\CRMCore\Services\ClientPipelineService;

class ClientPipelineQuery
{
    public function forClient(Customer $client): array { return app(ClientPipelineService::class)->summary($client); }
}
