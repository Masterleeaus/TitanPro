<?php

namespace Modules\TitanEchoAssist\Jobs;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\DTOs\MessagePayload;
use Modules\TitanEchoAssist\Services\ChannelRouter;

/**
 * Idempotent job that syncs a queued offline message when the client reconnects.
 *
 * Idempotency is guaranteed by an idempotency key derived from
 * (session_id + md5(message) + channel). A cache lock prevents concurrent
 * re-runs and a "processed" marker prevents duplicate execution across retries.
 */
class SyncOfflineConversationJob
{
    private const PROCESSED_PREFIX = 'echoassist:offline_sync:processed:';
    private const LOCK_PREFIX      = 'echoassist:offline_sync:lock:';
    private const PROCESSED_TTL    = 86400 * 7; // 7 days

    public function __construct(
        private readonly string $idempotencyKey,
        private readonly array  $payload,
    ) {}

    /**
     * Build an idempotency key from the message payload.
     */
    public static function buildIdempotencyKey(string $sessionId, string $message, string $channel): string
    {
        return $sessionId . ':' . md5($message) . ':' . $channel;
    }

    /**
     * Execute the job. Returns true when a message was processed, false when
     * it was skipped because the idempotency key was already handled.
     */
    public function handle(): bool
    {
        $lockKey      = self::LOCK_PREFIX . $this->idempotencyKey;
        $processedKey = self::PROCESSED_PREFIX . $this->idempotencyKey;

        // Already processed: skip without side effects
        if (Cache::get($processedKey)) {
            Log::debug('SyncOfflineConversationJob: skipping duplicate', [
                'idempotency_key' => $this->idempotencyKey,
            ]);
            return false;
        }

        // Acquire a short-lived lock to avoid concurrent duplicate processing
        $acquired = Cache::add($lockKey, 1, 30);
        if (! $acquired) {
            Log::debug('SyncOfflineConversationJob: lock held by another worker', [
                'idempotency_key' => $this->idempotencyKey,
            ]);
            return false;
        }

        try {
            $messagePayload = MessagePayload::fromArray($this->payload);

            /** @var ChannelRouter $router */
            $router = app(ChannelRouter::class);
            $router->route($messagePayload->channel, $messagePayload->toArray());

            // Mark as processed to prevent future duplicate runs
            Cache::put($processedKey, true, self::PROCESSED_TTL);

            Log::info('SyncOfflineConversationJob: synced offline message', [
                'idempotency_key' => $this->idempotencyKey,
                'channel'         => $messagePayload->channel,
                'session_id'      => $messagePayload->sessionId,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('SyncOfflineConversationJob: failed', [
                'idempotency_key' => $this->idempotencyKey,
                'error'           => $e->getMessage(),
            ]);
            throw $e;
        } finally {
            Cache::forget($lockKey);
        }
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
