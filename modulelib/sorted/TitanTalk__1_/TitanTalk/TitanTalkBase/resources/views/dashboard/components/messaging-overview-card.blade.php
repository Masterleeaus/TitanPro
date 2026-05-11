@php
    $stats = $messagingStats ?? ['sent_today' => 0, 'replies_today' => 0, 'pending_followups' => 0, 'channels' => []];
@endphp

<x-card class="w-full" id="titantalk-messaging-summary" size="lg">
    <div class="flex justify-between max-lg:flex-wrap lg:mb-7">
        <h3 class="items-center text-[17px] leading-6 lg:mb-0">@lang('Messaging Overview')</h3>
        <div class="flex w-full justify-between max-lg:flex-wrap lg:w-3/5">
            <div class="relative flex grow flex-col justify-center lg:ps-12 lg:after:absolute lg:after:right-0 lg:after:h-[80%] lg:after:w-px lg:after:bg-border">
                <p class="text-nowrap text-sm leading-5">@lang('Sent Today')</p>
                <h2>{{ $stats['sent_today'] }}</h2>
            </div>
            <div class="relative flex grow flex-col justify-center lg:ps-12 lg:after:absolute lg:after:right-0 lg:after:h-[80%] lg:after:w-px lg:after:bg-border">
                <p class="text-nowrap text-sm leading-5">@lang('Replies')</p>
                <h2>{{ $stats['replies_today'] }}</h2>
            </div>
            <div class="flex grow flex-col justify-center lg:ps-12">
                <p class="text-nowrap text-sm leading-5">@lang('Pending Follow-ups')</p>
                <h2>{{ $stats['pending_followups'] }}</h2>
            </div>
        </div>
    </div>

    <hr>

    <div class="flex flex-col gap-4 sm:py-6">
        <h4 class="text-foreground/80">@lang('Channel Breakdown')</h4>
        <div class="inline-flex flex-wrap gap-7 px-2 pt-1 max-sm:gap-3">
            @foreach(($stats['channels'] ?? []) as $channel => $count)
                <div class="inline-flex items-center gap-2">
                    <span class="size-2.5 rounded-sm bg-accent"></span>
                    <span class="text-sm leading-5 text-heading-foreground">{{ ucfirst($channel) }}</span>
                    <span class="leading-5 text-foreground/70">{{ $count }}</span>
                </div>
            @endforeach
        </div>
    </div>
</x-card>
