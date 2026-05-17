<?php

namespace Modules\JobManager\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


namespace ModulesJobManagerServices;


namespace Modules\JobManager\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookSender
{
    public static function send(array $payload): array
    {
        $url = config('jobmanager.webhook_url');
        if (!$url) {
            return ['ok'=>false,'error'=>'Webhook URL not configured'];
        }

        $retries = (int) config('jobmanager.webhook_retries', 3);
        $backoff = (int) config('jobmanager.webhook_backoff_seconds', 5);

        $lastErr = null;
        for ($i = 0; $i <= $retries; $i++) {
            try {
                $resp = Http::timeout(10)->asJson()->post($url, $payload);
                if ($resp->successful()) {
                    return ['ok'=>true,'status'=>$resp->status(),'body'=>$resp->json()];
                }
                $lastErr = 'HTTP '.$resp->status().' '.substr($resp->body(),0,200);
            } catch (\Throwable $e) {
                $lastErr = $e->getMessage();
            }
            if ($i < $retries) {
                sleep(max(0, $backoff));
            }
        }
        Log::warning('[JobManager] Webhook failed after retries', ['error'=>$lastErr]);
        return ['ok'=>false,'error'=>$lastErr];
    }
}
