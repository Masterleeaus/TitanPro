<div class="space-y-3">
    <div class="flex items-center justify-between">
        <strong class="text-sm">Plugin Registry</strong>
        <span class="text-[11px] text-black/40">Connected</span>
    </div>

    <template x-if="activePlugin">
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-3">
            <strong class="block text-sm">Active Plugin</strong>
            <span class="text-xs text-black/60" x-text="activePlugin"></span>
        </div>
    </template>

    <template x-for="plugin in plugins" :key="plugin.key">
        <div class="rounded-xl border border-black/10 px-3 py-3">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <strong class="block text-sm" x-text="plugin.label"></strong>
                    <span class="text-xs text-black/55" x-text="plugin.key"></span>
                </div>
                <span class="rounded-full bg-black/5 px-3 py-1 text-[11px]" x-text="plugin.group"></span>
            </div>
        </div>
    </template>
</div>
