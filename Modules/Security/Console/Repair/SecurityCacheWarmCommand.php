<?php

namespace Modules\Security\Console\Repair;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;

class SecurityCacheWarmCommand extends Command
{
    protected $signature = 'security:cache-warm';

    protected $description = 'Warm operational cache entries used by Security dashboards and health checks.';

    public function handle(SecurityModuleServiceInterface $security): int
    {
        $ttl = (int) config('security_production.performance.cache_ttl_seconds', 300);

        Cache::put('security:features', $security->features(), $ttl);
        Cache::put('security:permissions', $security->permissions(), $ttl);
        Cache::put('security:health', $security->health(), $ttl);
        Cache::put('security:status', $security->status(), $ttl);

        $this->info('Security module cache warmed.');

        return self::SUCCESS;
    }
}
