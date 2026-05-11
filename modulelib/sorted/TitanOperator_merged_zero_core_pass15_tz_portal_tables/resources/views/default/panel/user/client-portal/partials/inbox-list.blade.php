<div class="space-y-3">
    @forelse ($recentConversations as $conversation)
        <x-card class="mb-0">
            <div class="px-5 py-5">
                <div class="mb-2 flex items-center justify-between gap-3">
                    <div>
                        <p class="mb-1 font-medium">{{ data_get($conversation, 'conversation_name', __('Untitled conversation')) }}</p>
                        <p class="mb-0 text-2xs text-heading-foreground/70">{{ data_get($conversation, 'operatorChannel.channel', __('web')) }}</p>
                    </div>
                    @if(data_get($conversation, 'pinned'))
                        <span class="rounded-full border px-3 py-1 text-2xs">{{ __('Pinned') }}</span>
                    @endif
                </div>
                <p class="mb-1 text-2xs text-heading-foreground/60">{{ data_get($conversation, 'customer.name', __('Guest customer')) }}</p>
                <p class="mb-0 text-2xs text-heading-foreground/70">{{ \Illuminate\Support\Str::limit((string) data_get($conversation, 'lastMessage.message', __('No messages yet.')), 140) }}</p>
            </div>
        </x-card>
    @empty
        @include('titan_operator::default.panel.user.client-portal.partials.empty-state', ['message' => __('Inbox is empty right now.')])
    @endforelse
</div>
