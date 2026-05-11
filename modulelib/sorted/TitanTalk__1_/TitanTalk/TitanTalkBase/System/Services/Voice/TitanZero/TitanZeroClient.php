<?php

namespace App\Extensions\MarketingBot\System\Services\Voice\TitanZero;

use Illuminate\Support\Facades\Http;

class TitanZeroClient
{
    public function requestVoicemailSummary(array $payload): ?array
    {
        $endpoint = (string) config('titantalk.titan_zero.endpoint', '');
        if (!$endpoint) {
            return null;
        }

        $resp = Http::timeout(60)->post($endpoint, $payload);
        if (!$resp->ok()) {
            return null;
        }

        return $resp->json();
    }
}
