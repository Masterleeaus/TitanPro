<div class="space-y-4">
    <div class="rounded-xl border border-black/10 px-3 py-3">
        <div class="flex items-center justify-between gap-3">
            <strong class="text-sm">Live Action</strong>
            <span class="text-[11px] uppercase text-black/50" x-text="activeState"></span>
        </div>
        <p class="m-0 mt-2 text-sm text-black/70" x-text="activePrompt"></p>
    </div>

    <template x-for="assistant in assistants" :key="assistant.key">
        <div class="rounded-xl border border-black/10 px-3 py-3">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <strong class="block text-sm" x-text="assistant.label"></strong>
                    <span class="text-xs text-black/55" x-text="assistant.focus"></span>
                </div>
                <span class="rounded-full bg-black/5 px-3 py-1 text-[11px]" x-text="assistant.status"></span>
            </div>
        </div>
    </template>
</div>
