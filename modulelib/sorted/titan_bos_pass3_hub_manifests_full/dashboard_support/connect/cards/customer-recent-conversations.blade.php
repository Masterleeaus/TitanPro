@php
    $recentConversations = $recentConversations ?? [];
@endphp

<x-card class="col-span-full" class:body="p-0">
    <x-slot:head class="flex justify-between gap-1 px-8 py-6">
        <h3 class="m-0">{{ __('Recent Customer Conversations') }}</h3>

        <x-button variant="link" href="{{ route('dashboard.user.titan-talk.inbox', [], false) ?? 'javascript:void(0)' }}">
            {{ __('More') }}
            <svg class="opacity-50" width="20" height="19" viewBox="0 0 20 19" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" xmlns="http://www.w3.org/2000/svg"><path d="M0.833008 9.49998C0.833008 4.43737 4.93706 0.333313 9.99967 0.333313C15.0623 0.333313 19.1663 4.43737 19.1663 9.49998C19.1663 14.5626 15.0623 18.6666 9.99967 18.6666C4.93706 18.6666 0.833008 14.5626 0.833008 9.49998ZM9.33893 5.16072C9.01349 4.83529 8.48585 4.83529 8.16042 5.16072C7.83498 5.48616 7.83498 6.0138 8.16042 6.33923L11.3212 9.49998L8.16042 12.6607C7.83498 12.9862 7.83498 13.5138 8.16042 13.8392C8.48585 14.1647 9.01349 14.1647 9.33893 13.8392L13.0889 10.0892C13.4144 9.7638 13.4144 9.23616 13.0889 8.91072L9.33893 5.16072Z" /></svg>
        </x-button>
    </x-slot:head>

    @forelse ($recentConversations as $conversation)
        @php
            $status = data_get($conversation, 'status', 'waiting');
            $badge = match($status) {
                'replied', 'resolved', 'processed' => 'text-green-500 border-green-500',
                'escalated', 'handoff', 'operator_assigned' => 'text-yellow-500 border-yellow-500',
                'waiting', 'pending', 'awaiting_user' => 'text-blue-500 border-blue-500',
                default => 'text-gray-500 border-gray-500',
            };
        @endphp
        <div class="group relative flex flex-wrap items-center justify-between gap-2 border-b px-8 py-6 last:border-b-0">
            <div>
                <h3 class="mb-0 text-sm font-medium">
                    {{ data_get($conversation, 'contact_name', __('Unknown Contact')) }}
                    <x-tabler-arrow-right class="inline size-5 -translate-x-1 align-middle opacity-0 transition-all group-hover:translate-x-0 group-hover:opacity-100" />
                </h3>
                <p class="mb-0 text-2xs opacity-70">
                    {{ data_get($conversation, 'action_label', data_get($conversation, 'last_message_preview', __('No recent preview'))) }}
                </p>
            </div>

            <div class="flex items-center gap-3.5 text-2xs opacity-80">
                <span>{{ data_get($conversation, 'time_label', data_get($conversation, 'updated_at_label', __('Now'))) }}</span>
                <span @class(['inline-flex items-center gap-1.5 rounded-full border px-2 py-1', $badge])>
                    <x-tabler-circle-dashed class="size-4" />
                    {{ str(data_get($conversation, 'status', 'waiting'))->replace('_', ' ')->title() }}
                </span>
            </div>

            @if (!empty(data_get($conversation, 'url')))
                <a class="absolute inset-0 z-1" href="{{ data_get($conversation, 'url') }}"></a>
            @endif
        </div>
    @empty
        <div class="px-8 py-6 text-center text-base font-semibold">{{ __('No recent conversations found') }}</div>
    @endforelse
</x-card>
