<link rel="manifest" href="{{ $clientPortalRuntime['manifest_url'] ?? asset('pwa-runtime/manifest.webmanifest') }}">
<meta name="theme-color" content="#272733">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">

<meta name="client-portal" content="{{ $clientPortalRuntime['builder_url'] ?? route('dashboard.user.client_portal.builder') }}">
<meta name="client-portal-preview" content="{{ $clientPortalRuntime['preview_url'] ?? route('dashboard.user.client_portal.preview') }}">
<meta name="titan-runtime-manifest" content="{{ $clientPortalRuntime['manifest_url'] ?? asset('pwa-runtime/manifest.webmanifest') }}">
<meta name="titan-runtime-service-worker" content="{{ $clientPortalRuntime['service_worker_url'] ?? asset('pwa-runtime/sw.js') }}">
<meta name="titan-zero-bootstrap" content="{{ $clientPortalRuntime['zero_bootstrap_url'] ?? route('dashboard.user.titanzero.api.pwa.bootstrap') }}">
<meta name="titan-zero-handshake" content="{{ $clientPortalRuntime['zero_handshake_url'] ?? route('dashboard.user.titanzero.api.pwa.handshake') }}">
<meta name="titan-zero-sync" content="{{ $clientPortalRuntime['zero_sync_url'] ?? route('dashboard.user.titanzero.api.signals.ingest') }}">
<meta name="titan-zero-blobs" content="{{ $clientPortalRuntime['zero_blob_url'] ?? route('dashboard.user.titanzero.api.pwa.blobs.ingest') }}">
<meta name="titan-runtime-version" content="{{ $clientPortalRuntime['app_version'] ?? 'pass14' }}">

<script>
window.TitanPortalRuntimeConfig = @json($clientPortalRuntime ?? []);
(() => {
    if (!('serviceWorker' in navigator)) return;
    const serviceWorkerUrl = document.querySelector('meta[name="titan-runtime-service-worker"]')?.content || "{{ asset('pwa-runtime/sw.js') }}";
    window.addEventListener('load', () => {
        navigator.serviceWorker.register(serviceWorkerUrl).catch(() => {});
    });
})();
</script>
