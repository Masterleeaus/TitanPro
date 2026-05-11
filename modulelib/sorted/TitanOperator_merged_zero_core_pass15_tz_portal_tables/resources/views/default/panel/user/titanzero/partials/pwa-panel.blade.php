<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><strong>TitanZero PWA</strong></div>
    <div class="card-body">
        <p class="text-muted small mb-3">{{ $pwa['offline_label'] ?? 'Offline-ready shell.' }}</p>
        <p class="text-muted small mb-3">{{ $pwa['voice_label'] ?? 'Voice guide unavailable.' }}</p>
        <div class="d-grid gap-2 mb-3">
            <a class="btn btn-outline-primary" href="{{ $pwa['manifest_url'] ?? '#' }}">Manifest</a>
            <a class="btn btn-outline-primary" href="{{ $pwa['service_worker_url'] ?? '#' }}">Service Worker</a>
        </div>
        <div class="small text-uppercase text-muted mb-2">App Shortcuts</div>
        <div class="d-flex flex-wrap gap-2">
            @foreach(($pwa['shortcuts'] ?? []) as $shortcut)
                <a class="btn btn-sm btn-light border" href="{{ $shortcut['url'] }}">{{ $shortcut['label'] }}</a>
            @endforeach
        </div>
    </div>
</div>
