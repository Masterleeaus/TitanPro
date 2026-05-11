@php
    $quickActions = $quickActions ?? [
        ['key' => 'running_late', 'title' => 'Running Late', 'preview' => 'Running around 10 minutes late.', 'channel' => 'WhatsApp', 'icon' => 'clock-hour-4'],
        ['key' => 'on_my_way', 'title' => 'On My Way', 'preview' => 'On my way now. See you soon.', 'channel' => 'WhatsApp', 'icon' => 'send'],
        ['key' => 'confirm_job', 'title' => 'Confirm Job', 'preview' => 'Just confirming your booking time.', 'channel' => 'SMS', 'icon' => 'circle-check'],
        ['key' => 'reschedule', 'title' => 'Reschedule', 'preview' => 'Need to move this appointment.', 'channel' => 'WhatsApp', 'icon' => 'calendar-time'],
    ];
@endphp

<x-card class="col-span-full w-full" class:body="px-8" id="titantalk-quick-actions" size="lg">
    <x-slot:head class="flex justify-between gap-1 px-8 py-6">
        <h3 class="m-0">{{ __('Customer Communication Actions') }}</h3>

        <x-button variant="link" href="{{ route('dashboard.user.titan-talk.dashboard', [], false) ?? 'javascript:void(0)' }}">
            {{ __('More') }}
            <svg class="opacity-50" width="20" height="19" viewBox="0 0 20 19" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" xmlns="http://www.w3.org/2000/svg"><path d="M0.833008 9.49998C0.833008 4.43737 4.93706 0.333313 9.99967 0.333313C15.0623 0.333313 19.1663 4.43737 19.1663 9.49998C19.1663 14.5626 15.0623 18.6666 9.99967 18.6666C4.93706 18.6666 0.833008 14.5626 0.833008 9.49998ZM9.33893 5.16072C9.01349 4.83529 8.48585 4.83529 8.16042 5.16072C7.83498 5.48616 7.83498 6.0138 8.16042 6.33923L11.3212 9.49998L8.16042 12.6607C7.83498 12.9862 7.83498 13.5138 8.16042 13.8392C8.48585 14.1647 9.01349 14.1647 9.33893 13.8392L13.0889 10.0892C13.4144 9.7638 13.4144 9.23616 13.0889 8.91072L9.33893 5.16072Z" /></svg>
        </x-button>
    </x-slot:head>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($quickActions as $action)
            <button
                type="button"
                class="group relative rounded-xl border bg-background px-5 py-5 text-start transition-all hover:-translate-y-0.5 hover:border-heading-foreground/20 hover:shadow-lg"
                data-preset="{{ $action['key'] }}"
                @if(!empty($action['modal_target'])) data-modal-target="{{ $action['modal_target'] }}" @endif
            >
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div class="inline-flex size-11 items-center justify-center rounded-full border bg-foreground/5 text-heading-foreground">
                        <x-dynamic-component :component="'tabler-' . ($action['icon'] ?? 'message')" class="size-5" />
                    </div>
                    <span class="rounded-full border px-2 py-1 text-2xs opacity-70">{{ $action['channel'] ?? 'Default' }}</span>
                </div>
                <h4 class="mb-2 text-sm font-medium">{{ __($action['title']) }}</h4>
                <p class="mb-0 text-2xs opacity-70">{{ __($action['preview']) }}</p>
                <span class="mt-4 inline-flex items-center gap-1 text-2xs font-medium opacity-80">
                    {{ __('Send now') }}
                    <x-tabler-arrow-right class="size-4 transition-transform group-hover:translate-x-0.5" />
                </span>
            </button>
        @endforeach
    </div>
</x-card>
