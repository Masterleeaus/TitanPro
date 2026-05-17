<x-card class="mb-0">
    <div class="px-5 py-5">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div>
                <p class="mb-1 text-2xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Build Steps') }}</p>
                <h3 class="mb-0 font-heading text-lg font-semibold">{{ __('Use the existing chatbot builder flow') }}</h3>
            </div>
            <span class="rounded-full border px-3 py-1 text-2xs">{{ data_get($clientPortalRuntime, 'step_count', count($builderSteps ?? [])) }} {{ __('steps') }}</span>
        </div>

        <div class="grid grid-cols-1 gap-3 lg:grid-cols-5">
            @foreach ($builderSteps as $step)
                <div class="rounded-2xl border border-heading-foreground/10 p-4">
                    <p class="mb-1 text-2xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ $loop->iteration }}</p>
                    <h4 class="mb-2 font-medium">{{ data_get($step, 'title') }}</h4>
                    <p class="mb-0 text-2xs text-heading-foreground/70">{{ data_get($step, 'summary') }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-card>
