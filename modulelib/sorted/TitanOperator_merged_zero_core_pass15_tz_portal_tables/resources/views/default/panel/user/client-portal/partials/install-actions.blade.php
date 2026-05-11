<x-card class="mb-0">
    <div class="px-5 py-5">
        <p class="mb-1 text-2xs uppercase tracking-[0.2em] text-heading-foreground/60">{{ __('Launch actions') }}</p>
        <h3 class="mb-4 font-heading text-lg font-semibold">{{ __('Move from build to live runtime') }}</h3>
        <div class="flex flex-wrap gap-3">
            <x-button href="{{ data_get($clientPortalRuntime, 'builder_url') }}" variant="outline">{{ __('Open Builder') }}</x-button>
            <x-button href="{{ data_get($clientPortalRuntime, 'preview_url') }}" variant="outline">{{ __('Preview Runtime') }}</x-button>
            <x-button href="{{ route('dashboard.user.client_portal.templates') }}" variant="outline">{{ __('Switch Template') }}</x-button>
        </div>
    </div>
</x-card>
