@php
    $campaignFocus = $campaignFocus ?? [
        'title' => 'Follow-up Campaign Ready',
        'description' => 'Zero recommends sending a quick follow-up to pending confirmations and warm leads.',
        'audience_count' => 0,
        'sent_count' => 0,
        'reply_count' => 0,
        'conversion_count' => 0,
        'primary_label' => 'Launch',
        'primary_url' => 'javascript:void(0)',
        'secondary_label' => 'Edit',
        'secondary_url' => 'javascript:void(0)',
        'tertiary_label' => 'View Replies',
        'tertiary_url' => 'javascript:void(0)',
    ];
@endphp

<x-card class="col-span-full overflow-hidden" size="lg">
    <div class="relative z-0 flex flex-wrap items-center justify-between gap-6 rounded-xl border bg-cover bg-center px-4 py-4 lg:rounded-2xl lg:px-6 lg:py-5">
        <div class="max-lg:mb-1 inline-flex size-11 items-center justify-center rounded-full border bg-foreground/5 text-heading-foreground">
            <x-tabler-speakerphone class="size-5" />
        </div>

        <div class="flex grow flex-col items-start justify-center gap-2 text-start lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-3xl">
                <h3 class="m-0 text-sm font-semibold">{{ __($campaignFocus['title'] ?? 'Pipeline & Campaign Focus') }}</h3>
                <p class="mb-0 mt-2 text-xs opacity-80">{{ __($campaignFocus['description'] ?? '') }}</p>
                <div class="mt-4 flex flex-wrap gap-2 text-2xs opacity-80">
                    <span class="rounded-full border px-2 py-1">{{ __('Audience') }} {{ $campaignFocus['audience_count'] ?? 0 }}</span>
                    <span class="rounded-full border px-2 py-1">{{ __('Sent') }} {{ $campaignFocus['sent_count'] ?? 0 }}</span>
                    <span class="rounded-full border px-2 py-1">{{ __('Replies') }} {{ $campaignFocus['reply_count'] ?? 0 }}</span>
                    <span class="rounded-full border px-2 py-1">{{ __('Conversions') }} {{ $campaignFocus['conversion_count'] ?? 0 }}</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 lg:justify-end">
                <x-button href="{{ $campaignFocus['primary_url'] ?? 'javascript:void(0)' }}">{{ __($campaignFocus['primary_label'] ?? 'Launch') }}</x-button>
                <x-button variant="ghost-shadow" href="{{ $campaignFocus['secondary_url'] ?? 'javascript:void(0)' }}">{{ __($campaignFocus['secondary_label'] ?? 'Edit') }}</x-button>
                <x-button variant="link" href="{{ $campaignFocus['tertiary_url'] ?? 'javascript:void(0)' }}">{{ __($campaignFocus['tertiary_label'] ?? 'View Replies') }}</x-button>
            </div>
        </div>
    </div>
</x-card>
