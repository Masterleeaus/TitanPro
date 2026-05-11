<x-card class="mb-0">
    <div class="grid grid-cols-1 gap-4 px-5 py-5 lg:grid-cols-4">
        <div class="rounded-2xl border p-4">
            <p class="mb-1 font-medium">{{ __('Manifest URL') }}</p>
            <p class="mb-0 break-all text-2xs text-heading-foreground/70">{{ data_get($clientPortalRuntime, 'manifest_url') }}</p>
        </div>
        <div class="rounded-2xl border p-4">
            <p class="mb-1 font-medium">{{ __('Service worker') }}</p>
            <p class="mb-0 break-all text-2xs text-heading-foreground/70">{{ data_get($clientPortalRuntime, 'service_worker_url') }}</p>
        </div>
        <div class="rounded-2xl border p-4">
            <p class="mb-1 font-medium">{{ __('Shell entry') }}</p>
            <p class="mb-0 break-all text-2xs text-heading-foreground/70">{{ data_get($clientPortalRuntime, 'shell_url') }}</p>
        </div>
        <div class="rounded-2xl border p-4">
            <p class="mb-1 font-medium">{{ __('Status API') }}</p>
            <p class="mb-0 break-all text-2xs text-heading-foreground/70">{{ data_get($clientPortalRuntime, 'status_url') }}</p>
        </div>
    </div>
</x-card>
