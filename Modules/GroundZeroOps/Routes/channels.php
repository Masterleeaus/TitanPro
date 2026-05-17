<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('groundzeroops.company.{companyId}', function ($user, int $companyId): bool {
    return (int) ($user->company_id ?? 0) === $companyId;
});
