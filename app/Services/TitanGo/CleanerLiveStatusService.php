<?php

namespace App\Services\TitanGo;

use Illuminate\Support\Facades\Cache;
use Modules\TitanGoField\Models\FieldJob;

class CleanerLiveStatusService
{
    public function key(int|string|null $userId): string
    {
        return 'titango:cleaner-live-status:'.($userId ?: 'guest');
    }

    /** @param array<string, mixed> $extra @return array<string, mixed> */
    public function update(int|string|null $userId, string $status, ?FieldJob $job = null, array $extra = []): array
    {
        $payload = array_merge([
            'user_id' => $userId,
            'status' => $status,
            'job_id' => $job?->id,
            'job_reference' => $job?->reference,
            'updated_at' => now()->toIso8601String(),
            'source' => 'titango-cleaner-pwa',
        ], $extra);

        Cache::put($this->key($userId), $payload, now()->addHours(14));

        return $payload;
    }

    /** @return array<string, mixed> */
    public function get(int|string|null $userId): array
    {
        return Cache::get($this->key($userId), [
            'user_id' => $userId,
            'status' => 'offline',
            'updated_at' => null,
            'source' => 'titango-cleaner-pwa',
        ]);
    }
}
