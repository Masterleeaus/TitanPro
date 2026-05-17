<?php
namespace App\Extensions\TitanPulse\System\Services\Locks;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
class PulseLock {
    public function __construct(private CacheRepository $cache) {}
    public function acquire(string $key, int $seconds=300): bool { return (bool) $this->cache->add('titan_pulse_lock:'.$key, now()->timestamp, $seconds); }
    public function release(string $key): void { $this->cache->forget('titan_pulse_lock:'.$key); }
}
