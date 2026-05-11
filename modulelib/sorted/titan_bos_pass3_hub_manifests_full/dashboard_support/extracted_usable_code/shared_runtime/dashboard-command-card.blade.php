<x-card class="flex w-full flex-col" id="titan-zero-command-surface" size="lg">
    <x-titan-runtime.runtime-shell hub="work">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="mb-1 text-xl font-semibold text-foreground">{{ __('Titan Zero') }}</p>
                <span class="text-xs leading-5 text-foreground/80">{{ __('Hub command surface for chat, prompts, plugins, and governed work requests.') }}</span>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-accent/[7%] px-3 py-1 text-[11px] font-medium text-foreground/70">
                <span class="inline-block size-2 rounded-full bg-accent"></span>{{ __('Work Hub') }}
            </span>
        </div>

        <div class="mt-4 space-y-4">
            @include('panel.user.titan-runtime.partials.context-strip')

            <div class="grid gap-4 lg:grid-cols-[1.35fr_.65fr]">
                <x-card size="sm" class:body="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-foreground">{{ __('Recent Responses') }}</span>
                        <span class="text-[11px] text-foreground/50">{{ __('Compact thread') }}</span>
                    </div>
                    @include('panel.user.titan-runtime.partials.chat-thread')
                </x-card>

                <x-card size="sm" class:body="flex flex-col gap-4">
                    <div class="rounded-xl border border-foreground/10 p-3">
                        <p class="mb-2 text-xs text-foreground/60">{{ __('Quick prompts and plugin-aware input.') }}</p>
                        @include('panel.user.titan-runtime.partials.prompt-bar')
                    </div>
                </x-card>
            </div>
        </div>
    </x-titan-runtime.runtime-shell>
</x-card>
