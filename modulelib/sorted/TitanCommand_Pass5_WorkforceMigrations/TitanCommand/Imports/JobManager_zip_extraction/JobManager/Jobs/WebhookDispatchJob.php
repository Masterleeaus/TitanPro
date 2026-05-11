<?php

namespace Modules\JobManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\JobManager\Services\WebhookSender;


namespace ModulesJobManagerJobs;


namespace Modules\JobManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Modules\JobManager\Services\WebhookSender;

class WebhookDispatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->onQueue('default');
    }

    public function backoff(): array
    {
        $b = (int) config('jobmanager.webhook_backoff_seconds', 5);
        return [$b, $b*2, $b*4];
    }

    public function tries(): int
    {
        return (int) config('jobmanager.webhook_retries', 3) + 1;
    }

    public function handle(): void
    {
        $res = WebhookSender::send($this->payload);
        if (!$res['ok']) {
            throw new \RuntimeException('WebhookDispatchJob failed: '.$res['error']);
        }
    }

    public function failed(\Throwable $e): void
    {
        try {
            DB::table('jobmanager_failed_webhooks')->insert([
                'payload' => json_encode($this->payload),
                'error' => substr($e->getMessage(),0,500),
                'created_at' => now(),
            ]);
        } catch (\Throwable $t) {
            Log::warning('[JobManager] failed to log webhook failure: '.$t->getMessage());
        }
    }
}
