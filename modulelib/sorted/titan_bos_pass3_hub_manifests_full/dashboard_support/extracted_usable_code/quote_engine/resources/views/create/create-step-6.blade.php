<div class="col-start-1 col-end-1 row-start-1 row-end-1 w-full transition duration-300" x-show="currentStep === 6" x-cloak>
    <h2 class="mb-5 text-center text-[24px] font-medium leading-[1.2em]">
        <span class="block text-[0.875em] opacity-50">Configure automation</span>
        What should happen after the customer responds?
    </h2>

    <div class="mb-4 rounded-[10px] bg-foreground/5 px-6 py-3">
        <label class="mb-2 block text-2xs opacity-50">Follow-up delay (hours)</label>
        <input class="w-full border-none bg-transparent text-sm font-medium" type="number" min="1" max="720" x-model="formData.follow_up_delay_hours">
    </div>

    <div class="mb-4 rounded-[10px] bg-foreground/5 px-6 py-3">
        <label class="mb-2 block text-2xs opacity-50">Follow-up channel</label>
        <select class="w-full border-none bg-transparent text-sm font-medium" x-model="formData.follow_up_channel">
            <template x-for="channel in followUpChannels" :key="channel"><option x-text="channel"></option></template>
        </select>
    </div>

    <div class="mb-4 flex flex-col gap-3 rounded-[10px] bg-foreground/5 px-6 py-4 text-sm font-medium">
        <label class="flex items-center gap-3"><input type="checkbox" x-model="formData.auto_create_booking"> <span>Create booking if accepted</span></label>
        <label class="flex items-center gap-3"><input type="checkbox" x-model="formData.auto_create_job"> <span>Create job if accepted or confirmed</span></label>
        <label class="flex items-center gap-3"><input type="checkbox" x-model="formData.auto_send_invoice"> <span>Auto-send invoice in invoice mode</span></label>
        <label class="flex items-center gap-3"><input type="checkbox" x-model="formData.auto_negotiate"> <span>Allow controlled negotiation</span></label>
    </div>

    <div class="rounded-[10px] bg-foreground/5 px-6 py-3">
        <label class="mb-2 block text-2xs opacity-50">Negotiation floor (%)</label>
        <input class="w-full border-none bg-transparent text-sm font-medium" type="number" min="0" max="100" x-model="formData.negotiation_floor_percent">
        <label class="mb-2 mt-4 block text-2xs opacity-50">Negotiation notes</label>
        <textarea class="w-full border-none bg-transparent text-sm font-medium min-h-[100px]" x-model="formData.negotiation_notes" placeholder="Rules for discounts, exclusions, booking confirmation, or invoice concessions."></textarea>
    </div>

    <x-button class="mt-5 w-full bg-gradient-to-r from-gradient-from via-gradient-via to-gradient-to py-[18px] text-xs font-medium leading-none text-primary-foreground" @click.prevent="nextStep()">Continue <x-tabler-arrow-right class="size-4" /></x-button>
</div>
