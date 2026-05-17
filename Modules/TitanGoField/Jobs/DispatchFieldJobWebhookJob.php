<?php

namespace Modules\TitanGoField\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DispatchFieldJobWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public readonly int $fieldJobId,
        public readonly int $companyId,
        public readonly string $topic,
        public readonly string $webhookUrl,
    ) {}

    public function handle(): void
    {
        $payload = [
            'company_id'   => $this->companyId,
            'field_job_id' => $this->fieldJobId,
            'topic'        => $this->topic,
            'fired_at'     => now()->toIso8601String(),
        ];

        $response = Http::timeout(10)->post($this->webhookUrl, $payload);

        if (! $response->successful()) {
            Log::warning("TitanGoField webhook failed for job #{$this->fieldJobId}", [
                'status' => $response->status(),
                'url'    => $this->webhookUrl,
            ]);
            $this->fail("Webhook returned HTTP {$response->status()}");
        }
    }
}
