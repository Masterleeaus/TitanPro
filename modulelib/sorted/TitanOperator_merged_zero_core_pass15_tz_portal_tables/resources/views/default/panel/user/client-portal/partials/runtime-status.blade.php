<div class="grid gap-4 md:grid-cols-4">
    <div class="rounded-2xl border border-heading-foreground/10 p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Manifest') }}</p>
        <p class="mt-2 text-sm">{{ $clientPortalRuntime['manifest_url'] ?? asset('pwa-runtime/manifest.webmanifest') }}</p>
    </div>
    <div class="rounded-2xl border border-heading-foreground/10 p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Service Worker') }}</p>
        <p class="mt-2 text-sm">{{ $clientPortalRuntime['service_worker_url'] ?? asset('pwa-runtime/sw.js') }}</p>
    </div>
    <div class="rounded-2xl border border-heading-foreground/10 p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Runtime Shell') }}</p>
        <p class="mt-2 text-sm">{{ $clientPortalRuntime['shell_url'] ?? route('dashboard.user.client_portal.index') }}</p>
    </div>
    <div class="rounded-2xl border border-heading-foreground/10 p-4">
        <p class="text-xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Selected Template') }}</p>
        <p class="mt-2 text-sm">{{ data_get($selectedPortalTemplate, 'title', __('Client Portal')) }}</p>
    </div>
</div>
