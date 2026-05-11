@php
    $campaign = $campaignFocus ?? null;
@endphp

<x-card class="flex w-full flex-col" id="titantalk-campaign-focus" size="md">
    <div class="w-fit rounded-card bg-accent/[7%] p-4 dark:bg-foreground/5">
        <x-tabler-speakerphone class="size-7 text-accent" />
    </div>
    <div class="flex flex-col gap-3 pt-4">
        <p class="mb-1 text-xl font-semibold text-foreground">
            {{ $campaign ? $campaign->name : __('Campaign Control') }}
        </p>
        <span class="text-xs leading-5 text-foreground/80">
            {{ $campaign ? __('Track the current campaign and jump straight into launch, edit, or review flows.') : __('Launch WhatsApp or Telegram campaigns and keep replies inside TitanTalk conversations.') }}
        </span>
        <div class="flex items-center gap-3 max-sm:flex-wrap">
            <p class="mb-0">
                <span class="opacity-60">{{ __('Status') }}:</span>
                {{ $campaign ? ucfirst($campaign->status->value ?? $campaign->status) : __('No active campaign') }}
            </p>
            <span class="bg-foreground/10 h-1 w-1 rounded-full max-sm:hidden"></span>
            <p class="mb-0">
                <span class="opacity-60">{{ __('Platform') }}:</span>
                {{ $campaign ? ucfirst($campaign->type->value ?? $campaign->type) : __('Multi-channel') }}
            </p>
        </div>
    </div>
    <div class="flex flex-col gap-6 sm:pt-7">
        <x-card class="w-full" class:body="grid grid-cols-3 gap-4 items-center" id="campaign-metrics" size="sm">
            <div>
                <span class="text-xs font-semibold text-foreground/70">@lang('Sent')</span>
                <div class="text-lg font-semibold">{{ $campaignMetrics['sent'] ?? 0 }}</div>
            </div>
            <div>
                <span class="text-xs font-semibold text-foreground/70">@lang('Replies')</span>
                <div class="text-lg font-semibold">{{ $campaignMetrics['replies'] ?? 0 }}</div>
            </div>
            <div>
                <span class="text-xs font-semibold text-foreground/70">@lang('Conversions')</span>
                <div class="text-lg font-semibold">{{ $campaignMetrics['conversions'] ?? 0 }}</div>
            </div>
        </x-card>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <x-button href="{{ route('dashboard.user.marketing-bot.whatsapp-campaign.create') }}" variant="ghost-shadow">@lang('Launch')</x-button>
            <x-button href="{{ route('dashboard.user.marketing-bot.dashboard') }}#campaigns-table" variant="ghost-shadow">@lang('View')</x-button>
            @if($campaign)
                <x-button href="{{ route('dashboard.user.marketing-bot.' . ($campaign->type->value ?? $campaign->type) . '-campaign.edit', $campaign->id) }}" variant="ghost-shadow">@lang('Edit')</x-button>
            @else
                <x-button href="{{ route('dashboard.user.marketing-bot.telegram-campaign.create') }}" variant="ghost-shadow">@lang('Plan')</x-button>
            @endif
            <x-button href="{{ route('dashboard.user.marketing-bot.analytics.index') }}" variant="ghost-shadow">@lang('Analytics')</x-button>
        </div>
    </div>
</x-card>
