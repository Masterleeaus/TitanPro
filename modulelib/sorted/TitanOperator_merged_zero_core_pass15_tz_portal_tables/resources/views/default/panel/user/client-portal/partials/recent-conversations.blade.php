<x-card class="mb-0">
    <div class="px-5 py-5">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h3 class="mb-0 font-heading text-lg font-semibold">{{ __('Recent conversations') }}</h3>
            <a href="{{ route('dashboard.user.client_portal.history') }}" class="text-2xs text-primary">{{ __('Open history') }}</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentConversations as $conversation)
                <div class="rounded-2xl border px-4 py-3">
                    <div class="mb-1 flex items-center justify-between gap-4">
                        <p class="mb-0 font-medium text-heading-foreground">{{ data_get($conversation, 'conversation_name', __('Untitled conversation')) }}</p>
                        <span class="text-2xs text-heading-foreground/60">{{ optional(data_get($conversation, 'last_activity_at'))->diffForHumans() ?? __('No activity') }}</span>
                    </div>
                    <p class="mb-1 text-2xs text-heading-foreground/70">{{ data_get($conversation, 'customer.name', __('Guest customer')) }}</p>
                    <p class="mb-0 text-2xs text-heading-foreground/60">{{ \Illuminate\Support\Str::limit((string) data_get($conversation, 'lastMessage.message', __('No messages yet.')), 100) }}</p>
                </div>
            @empty
                @include('titan_operator::default.panel.user.client-portal.partials.empty-state', ['message' => __('No recent conversations found.')])
            @endforelse
        </div>
    </div>
</x-card>
