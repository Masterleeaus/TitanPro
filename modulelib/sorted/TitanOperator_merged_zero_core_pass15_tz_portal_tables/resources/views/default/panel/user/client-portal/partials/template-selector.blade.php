<x-card class="mb-0">
    <div class="px-5 py-5">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div>
                <p class="mb-1 text-2xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Selected template') }}</p>
                <h3 class="mb-0 font-heading text-lg font-semibold">{{ data_get($selectedPortalTemplate, 'title', __('Client Portal')) }}</h3>
            </div>
            <span class="rounded-full border px-3 py-1 text-2xs">{{ data_get($selectedPortalTemplate, 'accent') }}</span>
        </div>

        <p class="mb-4 text-2xs text-heading-foreground/70">{{ data_get($selectedPortalTemplate, 'summary') }}</p>

        <div class="flex flex-wrap gap-2">
            @foreach (data_get($selectedPortalTemplate, 'channels', []) as $channel)
                <span class="rounded-full border px-3 py-1 text-2xs">{{ ucfirst($channel) }}</span>
            @endforeach
            @foreach (data_get($selectedPortalTemplate, 'training', []) as $source)
                <span class="rounded-full bg-heading-foreground/5 px-3 py-1 text-2xs">{{ strtoupper($source) }}</span>
            @endforeach
        </div>
    </div>
</x-card>
