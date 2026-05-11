<x-card class="flex w-full flex-col lg:w-[48%]" id="titan-zero-canvas-surface" size="md">
    <x-titan-runtime.runtime-shell hub="work">
        <div class="w-fit rounded-card bg-accent/[7%] p-4 dark:bg-foreground/5">
            <x-tabler-layout-kanban class="size-7 text-accent" />
        </div>

        <div class="flex flex-col gap-3 pt-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="mb-1 text-xl font-semibold text-foreground">{{ __('Titan Zero Canvas') }}</p>
                    <span class="text-xs leading-5 text-foreground/80">{{ __('Execution surface for previews, forms, tables, and generated outputs.') }}</span>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-accent/[7%] px-3 py-1 text-[11px] font-medium text-foreground/70">
                    <span class="inline-block size-2 rounded-full bg-accent"></span>{{ __('Render Surface') }}
                </span>
            </div>
        </div>

        <div class="pt-6">
            <x-card size="sm" class:body="flex min-h-[260px] flex-col gap-3">
                @include('panel.user.titan-runtime.partials.canvas-pane')
            </x-card>
        </div>
    </x-titan-runtime.runtime-shell>
</x-card>
