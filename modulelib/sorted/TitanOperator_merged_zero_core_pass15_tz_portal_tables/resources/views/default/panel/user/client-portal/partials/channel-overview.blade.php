<x-card class="mb-0">
    <div class="px-5 py-5">
        <h3 class="mb-4 font-heading text-lg font-semibold">{{ __('Channel overview') }}</h3>
        <div class="space-y-3">
            @forelse ($channelBreakdown as $channel)
                <div class="flex items-center justify-between gap-3 rounded-2xl border px-4 py-3">
                    <span class="font-medium capitalize">{{ str_replace('_', ' ', data_get($channel, 'channel', 'web')) }}</span>
                    <span class="rounded-full border px-3 py-1 text-2xs">{{ data_get($channel, 'total', 0) }}</span>
                </div>
            @empty
                @include('titan_operator::default.panel.user.client-portal.partials.empty-state', ['message' => __('No connected channels yet.')])
            @endforelse
        </div>
    </div>
</x-card>
