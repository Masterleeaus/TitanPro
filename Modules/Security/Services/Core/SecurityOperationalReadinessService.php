<?php

namespace Modules\Security\Services\Core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;
use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;
use Throwable;

class SecurityOperationalReadinessService implements OperationalReadinessServiceInterface
{
    public function __construct(private readonly SecurityModuleServiceInterface $security)
    {
    }

    public function isReady(): bool
    {
        return $this->report()['status'] === 'ready';
    }

    public function report(): array
    {
        $checks = [
            'health' => $this->healthCheck(),
            'config' => $this->configCheck(),
            'storage' => $this->storageCheck(),
            'routes' => $this->routeCheck(),
            'queues' => $this->queueCheck(),
        ];

        $failed = collect($checks)
            ->filter(fn (array $check) => ($check['status'] ?? 'fail') !== 'ok')
            ->keys()
            ->values()
            ->all();

        return [
            'module' => 'security',
            'status' => empty($failed) ? 'ready' : 'degraded',
            'failed' => $failed,
            'checks' => $checks,
        ];
    }

    private function healthCheck(): array
    {
        try {
            $health = $this->security->health();

            return [
                'status' => ($health['status'] ?? 'degraded') === 'ok' ? 'ok' : 'fail',
                'detail' => $health,
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'fail',
                'message' => $exception->getMessage(),
            ];
        }
    }

    private function configCheck(): array
    {
        $missing = [];

        foreach ((array) config('security_production.readiness.required_config', []) as $key) {
            if (Config::get($key) === null) {
                $missing[] = $key;
            }
        }

        return [
            'status' => empty($missing) ? 'ok' : 'fail',
            'missing' => $missing,
        ];
    }

    private function storageCheck(): array
    {
        $missing = [];

        foreach ((array) config('security_production.readiness.required_directories', []) as $directory) {
            $path = module_path('Security', $directory);
            if (! File::isDirectory($path)) {
                $missing[] = $directory;
            }
        }

        return [
            'status' => empty($missing) ? 'ok' : 'fail',
            'missing' => $missing,
        ];
    }

    private function routeCheck(): array
    {
        $routes = [
            module_path('Security', 'Routes/web.php'),
            module_path('Security', 'Routes/api.php'),
            module_path('Security', 'Routes/internal.php'),
        ];

        $missing = array_values(array_filter($routes, fn (string $path) => ! File::exists($path)));

        return [
            'status' => empty($missing) ? 'ok' : 'fail',
            'missing' => array_map('basename', $missing),
        ];
    }

    private function queueCheck(): array
    {
        $queue = (string) config('queue.default', 'sync');

        return [
            'status' => $queue !== '' ? 'ok' : 'fail',
            'driver' => $queue,
            'async_recommended' => $queue !== 'sync',
        ];
    }
}
