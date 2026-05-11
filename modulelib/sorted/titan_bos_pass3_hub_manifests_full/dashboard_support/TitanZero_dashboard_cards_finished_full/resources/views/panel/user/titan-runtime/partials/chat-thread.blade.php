<div class="max-h-[280px] min-h-[220px] space-y-3 overflow-y-auto pe-1">
    <template x-if="messages.length === 0">
        <div class="flex h-[200px] items-center justify-center rounded-xl border border-dashed border-black/10 px-4 text-center text-sm text-black/60">
            No conversation yet. Ask Titan Zero or use a quick prompt.
        </div>
    </template>
    <template x-for="(message, index) in messages" :key="index">
        <div class="rounded-xl border border-black/10 px-3 py-2">
            <div class="mb-1 flex items-center justify-between gap-3">
                <strong class="text-xs" x-text="message.role"></strong>
                <span class="text-[11px] text-black/40" x-text="message.time"></span>
            </div>
            <p class="m-0 text-sm text-black/80" x-text="message.body"></p>
        </div>
    </template>
</div>
