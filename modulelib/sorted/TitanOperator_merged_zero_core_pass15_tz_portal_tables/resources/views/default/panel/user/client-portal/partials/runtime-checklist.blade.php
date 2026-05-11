<x-card class="mb-0">
    <div class="px-5 py-5">
        <p class="mb-1 text-2xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Runtime readiness') }}</p>
        <h3 class="mb-4 font-heading text-lg font-semibold">{{ __('Portal shell, build steps, and inbox handoff') }}</h3>

        <div class="space-y-3">
            @foreach ($runtimeChecks as $check)
                <div class="rounded-2xl border border-heading-foreground/10 p-4">
                    <div class="mb-1 flex items-center justify-between gap-3">
                        <p class="mb-0 font-medium">{{ data_get($check, 'label') }}</p>
                        <span class="rounded-full border px-3 py-1 text-2xs {{ data_get($check, 'status') === 'ready' ? 'border-green-500/30 text-green-600' : '' }}">{{ strtoupper(data_get($check, 'status')) }}</span>
                    </div>
                    <p class="mb-0 text-2xs text-heading-foreground/70">{{ data_get($check, 'detail') }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-card>
