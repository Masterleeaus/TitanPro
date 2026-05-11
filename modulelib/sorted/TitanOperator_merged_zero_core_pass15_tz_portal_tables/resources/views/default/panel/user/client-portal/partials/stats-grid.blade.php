<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
    @foreach ([
        __('Open conversations') => data_get($portalStats, 'open_conversations', 0),
        __('Pinned chats') => data_get($portalStats, 'pinned_conversations', 0),
        __('Messages today') => data_get($portalStats, 'messages_today', 0),
        __('Pending workflows') => data_get($portalStats, 'pending_workflows', 0),
        __('Build steps') => data_get($clientPortalRuntime, 'step_count', 0),
    ] as $label => $value)
        <x-card class="mb-0">
            <div class="px-5 py-5">
                <p class="mb-1 text-2xs uppercase tracking-wide text-heading-foreground/60">{{ $label }}</p>
                <p class="mb-0 font-heading text-3xl font-semibold text-heading-foreground">{{ $value }}</p>
            </div>
        </x-card>
    @endforeach
</div>
