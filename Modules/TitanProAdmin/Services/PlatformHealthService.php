<?php

namespace Modules\TitanProAdmin\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PlatformHealthService
{
    public function status(): array
    {
        return [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => config('queue.default'),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    private function checkDatabase(): string
    {
        try {
            DB::select('select 1 as ok');

            return 'ok';
        } catch (\Throwable) {
            return 'down';
        }
    }

    private function checkCache(): string
    {
        try {
            $key = 'titanproadmin:health:'.str()->random(8);
            Cache::put($key, true, 5);
            Cache::forget($key);

            return 'ok';
        } catch (\Throwable) {
            return 'down';
        }
    }
}
