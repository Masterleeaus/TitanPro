<?php

namespace Modules\CRMCore\Services;

use App\Models\Customer;
use App\Models\Job;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\Lead;

class ClientPipelineService
{
    public function summary(Customer $client): array
    {
        return [
            'customer_id' => $client->getKey(),
            'customer_name' => $client->full_name,
            'leads' => Lead::query()->when($client->email, fn ($query) => $query->where('contact_email', $client->email))->count(),
            'deals' => Deal::query()->where('crmcore_customer_id', $client->getKey())->count(),
            'jobs' => Job::query()->where('customer_id', $client->getKey())->count(),
        ];
    }
}
