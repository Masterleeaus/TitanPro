<?php

namespace App\Support\TitanGo;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PwaFeatureCatalog
{
    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        $publicManifest = $this->readJson(public_path('manifest.json'));
        $buildManifest = $this->readJson(public_path('build/manifest.webmanifest'));
        $moduleManifests = $this->moduleManifests();

        return [
            'manifest' => [
                'name' => Arr::get($publicManifest, 'name', Arr::get($buildManifest, 'name', 'TitanGo')),
                'short_name' => Arr::get($publicManifest, 'short_name', Arr::get($buildManifest, 'short_name', 'TitanGo')),
                'start_url' => Arr::get($publicManifest, 'start_url', Arr::get($buildManifest, 'start_url', '/technician/dashboard')),
                'scope' => Arr::get($publicManifest, 'scope', Arr::get($buildManifest, 'scope', '/technician/')),
                'display' => Arr::get($publicManifest, 'display', Arr::get($buildManifest, 'display', 'standalone')),
                'theme_color' => Arr::get($publicManifest, 'theme_color', Arr::get($buildManifest, 'theme_color', '#0f172a')),
                'icons' => count(Arr::get($publicManifest, 'icons', Arr::get($buildManifest, 'icons', []))),
            ],
            'features' => $this->features(),
            'sync_routes' => $this->syncRoutes(),
            'cached_reads' => $this->cachedReads(),
            'module_manifests' => $moduleManifests,
            'module_capabilities' => $this->moduleCapabilities($moduleManifests),
            'links' => [
                ['label' => 'Open TitanGo Panel', 'url' => url('/titango')],
                ['label' => 'Open Technician PWA', 'url' => url('/technician/dashboard?admin_preview=1')],
                ['label' => 'PWA Manifest', 'url' => url('/manifest.json')],
                ['label' => 'Built SW', 'url' => url('/build/sw.js')],
            ],
        ];
    }

    /** @return array<int, array<string, string>> */
    private function features(): array
    {
        return [
            ['name' => 'Installable app shell', 'status' => 'Live', 'detail' => 'Standalone manifest, scoped technician launch, app icons and theme color.'],
            ['name' => 'Offline job reads', 'status' => 'Live', 'detail' => 'Caches technician job payloads for read access during weak connectivity.'],
            ['name' => 'Offline catalog reads', 'status' => 'Live', 'detail' => 'Caches active catalog/item data used by line-item workflows.'],
            ['name' => 'Background sync queue', 'status' => 'Live', 'detail' => 'Queues job writes while offline and replays them when the service worker reconnects.'],
            ['name' => 'Job status transitions', 'status' => 'Live', 'detail' => 'Supports en-route, in-progress, completion and guarded transition updates.'],
            ['name' => 'Technician and customer notes', 'status' => 'Live', 'detail' => 'PATCH endpoints are queue-aware for field notes and customer-facing notes.'],
            ['name' => 'Checklist completion', 'status' => 'Live', 'detail' => 'Offline-capable checklist toggles by job checklist item.'],
            ['name' => 'Line-item management', 'status' => 'Live', 'detail' => 'Create, update and delete line items from the field PWA.'],
            ['name' => 'Photo capture/upload', 'status' => 'Online required', 'detail' => 'Client-side compression and upload endpoint for before/after job photos.'],
            ['name' => 'Location sharing', 'status' => 'Live', 'detail' => 'Technician geolocation heartbeat powers dispatch map and sync health.'],
            ['name' => 'Admin preview bridge', 'status' => 'Live', 'detail' => 'Owners, admins and super admins can launch a technician preview from TitanGo.'],
        ];
    }

    /** @return array<int, array<string, string>> */
    private function cachedReads(): array
    {
        return [
            ['cache' => 'technician-jobs-api', 'route' => 'GET /api/technician/jobs*', 'ttl' => '8 hours'],
            ['cache' => 'technician-catalog-api', 'route' => 'GET /api/technician/catalog', 'ttl' => '24 hours'],
        ];
    }

    /** @return array<int, array<string, string>> */
    private function syncRoutes(): array
    {
        return [
            ['method' => 'PATCH', 'route' => '/api/technician/jobs/{job}/status', 'feature' => 'Status updates'],
            ['method' => 'PATCH', 'route' => '/api/technician/jobs/{job}/notes', 'feature' => 'Technician notes'],
            ['method' => 'PATCH', 'route' => '/api/technician/jobs/{job}/customer-notes', 'feature' => 'Customer notes'],
            ['method' => 'PATCH', 'route' => '/api/technician/jobs/{job}/checklist/{item}', 'feature' => 'Checklist toggles'],
            ['method' => 'POST', 'route' => '/api/technician/jobs/{job}/line-items', 'feature' => 'Create line item'],
            ['method' => 'PATCH', 'route' => '/api/technician/jobs/{job}/line-items/{lineItem}', 'feature' => 'Update line item'],
            ['method' => 'DELETE', 'route' => '/api/technician/jobs/{job}/line-items/{lineItem}', 'feature' => 'Delete line item'],
            ['method' => 'DELETE', 'route' => '/api/technician/jobs/{job}/photos/{attachment}', 'feature' => 'Delete photo'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function moduleManifests(): array
    {
        $paths = collect(File::glob(base_path('Modules/*/PWA/pwa.manifest.json')) ?: [])
            ->merge(File::glob(base_path('Modules/*/manifests/pwa.manifest.json')) ?: [])
            ->unique()
            ->values();

        return $paths->map(function (string $path): array {
            $data = $this->readJson($path);
            $module = (string) ($data['module'] ?? Str::of($path)->after(base_path('Modules/'))->before('/'));

            return [
                'module' => $module,
                'enabled' => (bool) ($data['enabled'] ?? false),
                'route' => $data['route'] ?? null,
                'screens' => array_values((array) ($data['screens'] ?? [])),
                'capabilities' => array_values((array) ($data['capabilities'] ?? [])),
                'path' => Str::after($path, base_path().DIRECTORY_SEPARATOR),
            ];
        })->values()->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $moduleManifests
     * @return array<int, string>
     */
    private function moduleCapabilities(array $moduleManifests): array
    {
        return collect($moduleManifests)
            ->flatMap(fn (array $manifest): array => (array) ($manifest['capabilities'] ?? []))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function readJson(string $path): array
    {
        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode((string) File::get($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}
