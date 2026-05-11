<?php

namespace App\Extensions\TitanOperator\System\Services\ClientPortal;

use Illuminate\Support\Facades\Route;

class ClientPortalRuntimeConfigService
{
    public function config(?string $selectedTemplate = null): array
    {
        return [
            'manifest_url' => asset('pwa-runtime/manifest.webmanifest'),
            'service_worker_url' => asset('pwa-runtime/sw.js'),
            'shell_url' => route('dashboard.user.client_portal.index'),
            'builder_url' => route('dashboard.user.client_portal.builder', array_filter(['template' => $selectedTemplate])),
            'preview_url' => route('dashboard.user.client_portal.preview', array_filter(['template' => $selectedTemplate])),
            'templates_url' => route('dashboard.user.client_portal.templates'),
            'status_url' => route('api.v2.client_portal.status', array_filter(['template' => $selectedTemplate])),
            'zero_bootstrap_url' => route('dashboard.user.titanzero.api.pwa.bootstrap'),
            'zero_handshake_url' => route('dashboard.user.titanzero.api.pwa.handshake'),
            'zero_sync_url' => route('dashboard.user.titanzero.api.signals.ingest'),
            'zero_blob_url' => route('dashboard.user.titanzero.api.pwa.blobs.ingest'),
            'selected_template' => $selectedTemplate,
            'app_version' => 'pass14',
        ];
    }

    public function checks(?string $selectedTemplate = null): array
    {
        $config = $this->config($selectedTemplate);

        return [
            [
                'key' => 'manifest',
                'label' => 'Manifest asset',
                'status' => $this->publishedAssetExists(public_path('pwa-runtime/manifest.webmanifest')) ? 'ready' : 'missing',
                'detail' => $config['manifest_url'],
            ],
            [
                'key' => 'service_worker',
                'label' => 'Service worker asset',
                'status' => $this->publishedAssetExists(public_path('pwa-runtime/sw.js')) ? 'ready' : 'missing',
                'detail' => $config['service_worker_url'],
            ],
            [
                'key' => 'zero_bootstrap',
                'label' => 'Zero bootstrap route',
                'status' => Route::has('dashboard.user.titanzero.api.pwa.bootstrap') ? 'ready' : 'missing',
                'detail' => $config['zero_bootstrap_url'],
            ],
            [
                'key' => 'zero_sync',
                'label' => 'Zero signal ingest route',
                'status' => Route::has('dashboard.user.titanzero.api.signals.ingest') ? 'ready' : 'missing',
                'detail' => $config['zero_sync_url'],
            ],
        ];
    }

    protected function publishedAssetExists(string $path): bool
    {
        return is_file($path);
    }
}
