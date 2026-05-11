<div class="flex items-start justify-between gap-3 max-sm:flex-col lg:mb-5">
    <div>
        <div class="mb-1 inline-flex items-center gap-2 rounded-full bg-foreground/5 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/60">
            <span class="inline-block size-2 rounded-full bg-accent"></span>
            @lang('Work Command Surface')
        </div>
        <h3 class="items-center text-[17px] leading-6 lg:mb-1">
            @lang('Work Wizard')
        </h3>
        <p class="m-0 text-xs leading-5 text-foreground/70">
            @lang('Plan, staff, and dispatch work directly inside the dashboard with guided steps and assistant recommendations.')
        </p>
    </div>
    <div class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1 text-[11px] font-medium text-foreground/70">
        <span class="inline-block size-2 rounded-full bg-emerald-500"></span>
        @lang('Inline and ready')
    </div>
</div>

<div class="mt-4 border-t border-border pt-4">
    @include('dashboard.partials.work-wizard-embed')
</div>
