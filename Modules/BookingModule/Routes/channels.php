<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('bookingmodule.company.{companyId}', function ($user, int $companyId) {
    return (int) ($user->company_id ?? 0) === $companyId;
});
