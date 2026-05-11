<div class="rounded-xl border border-black/10 px-3 py-3">
    <div class="flex items-center justify-between gap-3">
        <strong class="text-sm">Runtime Context</strong>
        <span class="text-[11px] text-black/50 uppercase" x-text="hub"></span>
    </div>
    <div class="mt-2 grid gap-2 md:grid-cols-2">
        <div class="rounded-lg bg-black/5 px-3 py-2">
            <strong class="block text-xs">Site Memory</strong>
            <span class="text-xs text-black/60" x-text="memory.site_memory.summary"></span>
        </div>
        <div class="rounded-lg bg-black/5 px-3 py-2">
            <strong class="block text-xs">Job Memory</strong>
            <span class="text-xs text-black/60" x-text="memory.job_memory.summary"></span>
        </div>
    </div>
</div>
