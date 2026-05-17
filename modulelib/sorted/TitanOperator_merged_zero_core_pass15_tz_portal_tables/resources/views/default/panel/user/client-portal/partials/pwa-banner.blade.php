<x-card class="mb-0">
    <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
        <div>
            <p class="mb-1 font-heading text-base font-semibold text-heading-foreground">{{ __('Installable client portal') }}</p>
            <p class="mb-0 text-2xs text-heading-foreground/70">{{ __('This portal uses the existing chatbot build flow, backed by TitanOperator data and TitanZero runtime assets.') }}</p>
        </div>
        <div class="flex flex-wrap gap-2 text-2xs">
            <span class="rounded-full border px-3 py-1">{{ __('Manifest ready') }}</span>
            <span class="rounded-full border px-3 py-1">{{ __('Service worker ready') }}</span>
            <span class="rounded-full border px-3 py-1">{{ __('Template: :name', ['name' => data_get($selectedPortalTemplate, 'title', __('Client Portal'))]) }}</span>
        </div>
    </div>
</x-card>
