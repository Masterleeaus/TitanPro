<x-card class="flex w-full flex-col" id="titantalk-quick-actions" size="md">
    <x-slot:head class="pb-0 pt-5 border-0">
        <div class="flex items-center justify-between">
            <h4 class="m-0 text-[17px]">{{ __('Quick Message Actions') }}</h4>
            <span class="text-2xs text-foreground/60">{{ __('Tap, preview, send') }}</span>
        </div>
    </x-slot:head>
    <div class="mb-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
        @foreach($quickPresets as $key => $preset)
            <x-card class="flex w-full flex-col relative cursor-pointer hover:border-primary/30" class:body="space-y-3" size="sm">
                <button
                    type="button"
                    class="absolute left-0 top-0 z-10 h-full w-full rounded-[inherit] text-start"
                    data-quick-action="{{ $key }}"
                    data-title="{{ $preset['title'] }}"
                    data-preview="{{ $preset['preview'] }}"
                    data-defaults='@json($preset['defaults'] ?? [])'
                ></button>
                <div class="!mt-0 flex items-center overflow-hidden rounded-full bg-accent/[7%] justify-center text-accent" style="width: 61px; height: 61px;">
                    <x-dynamic-component :component="'tabler-' . ($preset['icon'] ?? 'message')" class="size-8" />
                </div>
                <h4 class="font-medium max-sm:text-center">{{ $preset['title'] }}</h4>
                <div class="flex w-fit rounded-xl border px-2 py-1 max-sm:mx-auto">
                    <span class="text-center text-2xs">{{ $preset['channel_hint'] ?? 'TitanTalk' }}</span>
                </div>
            </x-card>
        @endforeach
    </div>

    <div class="rounded-card border border-dashed border-foreground/10 p-4">
        <div class="flex items-center gap-3 max-sm:flex-wrap">
            <div class="grow">
                <label class="mb-1 block text-2xs font-medium">@lang('Conversation / Contact')</label>
                <select id="titantalk-quick-conversation" class="lqd-input lqd-input-md w-full">
                    <option value="">@lang('Select conversation')</option>
                    @foreach($quickConversations as $conversation)
                        <option value="{{ $conversation->id }}">{{ $conversation->conversation_name ?: ('#' . $conversation->id) }} · {{ ucfirst($conversation->type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label class="mb-1 block text-2xs font-medium">@lang('Preview')</label>
                <textarea id="titantalk-quick-preview" class="lqd-input lqd-input-md h-[92px] w-full" readonly></textarea>
            </div>
        </div>
        <div class="mt-3 flex flex-wrap gap-3" id="titantalk-quick-inputs"></div>
        <div class="mt-4 flex items-center justify-between gap-3 max-sm:flex-wrap">
            <div class="text-2xs text-foreground/60" id="titantalk-quick-selected">@lang('Choose an action to preview the message.')</div>
            <x-button type="button" id="titantalk-quick-send" variant="primary">@lang('Send Quick Message')</x-button>
        </div>
    </div>
</x-card>
