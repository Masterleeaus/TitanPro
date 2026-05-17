<?php

namespace App\Services\TitanGo;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OfflineActionQueueService
{
    protected function key(int|string|null $userId): string
    {
        return 'titango:offline-actions:'.($userId ?: 'guest');
    }

    /** @param array<int, array<string, mixed>> $actions @return array<string, mixed> */
    public function accept(int|string|null $userId, array $actions): array
    {
        $existing = Cache::get($this->key($userId), []);
        $accepted = [];

        foreach ($actions as $action) {
            if (! is_array($action)) {
                continue;
            }

            $accepted[] = array_merge($action, [
                'server_id' => (string) Str::uuid(),
                'received_at' => now()->toIso8601String(),
                'server_status' => 'queued',
            ]);
        }

        $queue = array_slice(array_merge($accepted, $existing), 0, 250);
        Cache::put($this->key($userId), $queue, now()->addDays(7));

        return ['ok' => true, 'accepted' => count($accepted), 'queued' => count($queue)];
    }

    /** @return array<int, array<string, mixed>> */
    public function all(int|string|null $userId): array
    {
        return Cache::get($this->key($userId), []);
    }
}
