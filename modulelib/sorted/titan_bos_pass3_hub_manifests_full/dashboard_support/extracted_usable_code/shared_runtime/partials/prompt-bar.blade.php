<div class="space-y-3">
    <div class="flex flex-wrap gap-2">
        <template x-for="chip in quickPrompts" :key="chip">
            <button type="button" class="rounded-full border border-black/10 px-3 py-1.5 text-xs transition hover:bg-black/5" @click.prevent="sendPrompt(chip)" x-text="chip"></button>
        </template>
    </div>
    <div class="flex items-center gap-2">
        <input type="text" class="w-full rounded-xl border border-black/10 px-3 py-2 text-sm" placeholder="Ask Titan Runtime..." x-model="prompt" @keydown.enter.prevent="sendPrompt()">
        <button type="button" class="rounded-xl bg-black px-4 py-2 text-sm text-white" @click.prevent="sendPrompt()">Send</button>
    </div>
</div>
