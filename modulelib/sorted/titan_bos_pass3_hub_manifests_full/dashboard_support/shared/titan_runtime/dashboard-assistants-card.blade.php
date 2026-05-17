<x-card class="flex w-full flex-col lg:w-[48%]" id="titan-zero-active-assistants" size="md">
    <x-titan-runtime.runtime-shell hub="work">
        <div class="w-fit rounded-card bg-accent/[7%] p-4 dark:bg-foreground/5">
            <x-tabler-users-group class="size-7 text-accent" />
        </div>

        <div class="flex flex-col gap-3 pt-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="mb-1 text-xl font-semibold text-foreground">{{ __('Active Assistants') }}</p>
                    <span class="text-xs leading-5 text-foreground/80">{{ __('Current hub assistants with live action and execution state.') }}</span>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-accent/[7%] px-3 py-1 text-[11px] font-medium text-foreground/70">
                    <span class="inline-block size-2 rounded-full bg-accent"></span>{{ __('Hub Scoped') }}
                </span>
            </div>
        </div>

        <div class="pt-5">
            @include('panel.user.titan-runtime.partials.assistants-pane')
        </div>
    </x-titan-runtime.runtime-shell>
</x-card>
