@php
    $messagingStats = $messagingStats ?? [
        'sent_today' => 0,
        'replies_today' => 0,
        'pending_followups' => 0,
        'confirmations_outstanding' => 0,
        'channels' => [
            'whatsapp' => 0,
            'sms' => 0,
            'email' => 0,
            'voice' => 0,
        ],
    ];
@endphp

<div class="col-span-full grid w-full grid-cols-1 gap-2.5 md:grid-cols-2 xl:grid-cols-4" id="titantalk-messaging-overview">
    <x-card size="lg">
        <h3 class="mb-10 md:mb-20">{{ __('Messages Sent Today') }}</h3>
        <x-number-counter class="font-heading text-[34px] font-semibold leading-none" value="{{ data_get($messagingStats, 'sent_today', 0) }}" />
        <p class="mt-3 text-2xs opacity-70">{{ __('Across all channels') }}</p>
    </x-card>

    <x-card size="lg">
        <h3 class="mb-10 md:mb-20">{{ __('Replies Received') }}</h3>
        <x-number-counter class="font-heading text-[34px] font-semibold leading-none" value="{{ data_get($messagingStats, 'replies_today', 0) }}" />
        <p class="mt-3 text-2xs opacity-70">{{ __('Customer responses today') }}</p>
    </x-card>

    <x-card size="lg">
        <h3 class="mb-10 md:mb-20">{{ __('Pending Follow-ups') }}</h3>
        <x-number-counter class="font-heading text-[34px] font-semibold leading-none" value="{{ data_get($messagingStats, 'pending_followups', 0) }}" />
        <p class="mt-3 text-2xs opacity-70">{{ __('Waiting for operator or automation') }}</p>
    </x-card>

    <x-card size="lg">
        <h3 class="mb-10 md:mb-20">{{ __('Confirmations Outstanding') }}</h3>
        <x-number-counter class="font-heading text-[34px] font-semibold leading-none" value="{{ data_get($messagingStats, 'confirmations_outstanding', 0) }}" />
        <div class="mt-4 flex flex-wrap gap-2 text-2xs opacity-70">
            <span class="rounded-full border px-2 py-1">WA {{ data_get($messagingStats, 'channels.whatsapp', 0) }}</span>
            <span class="rounded-full border px-2 py-1">SMS {{ data_get($messagingStats, 'channels.sms', 0) }}</span>
            <span class="rounded-full border px-2 py-1">Email {{ data_get($messagingStats, 'channels.email', 0) }}</span>
            <span class="rounded-full border px-2 py-1">Voice {{ data_get($messagingStats, 'channels.voice', 0) }}</span>
        </div>
    </x-card>
</div>
