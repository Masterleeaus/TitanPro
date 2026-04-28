<?php

namespace Modules\CRMCore\Services;

use Modules\CRMCore\Support\HostServiceBridge;

class ServiceInterestResolver
{
    public function match(?string $interest)
    {
        return HostServiceBridge::match($interest);
    }

    public function suggestions(?string $interest): array
    {
        if (! $interest) {
            return [];
        }

        return HostServiceBridge::query()
            ->where('name', 'like', '%' . $interest . '%')
            ->limit(5)
            ->get()
            ->all();
    }
}
