<div class="col-start-1 col-end-1 row-start-1 row-end-1 w-full transition duration-300" x-show="currentStep === 3" x-cloak>
    <h2 class="mb-5 text-center text-[24px] font-medium leading-[1.2em]">
        <span class="block text-[0.875em] opacity-50">Price and conversion logic</span>
        Set the logic for this revenue flow
    </h2>

    <div class="mb-4 grid grid-cols-1 gap-3">
        <select class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" x-model="formData.pricing_model">
            <option value="fixed_scope">Fixed scope</option>
            <option value="hourly_estimate">Hourly estimate</option>
            <option value="sqm_or_area">Area based</option>
            <option value="visit_plus_addons">Visit fee + add-ons</option>
        </select>

        <input class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" type="number" step="0.5" x-model="formData.estimated_hours" placeholder="Estimated labour hours">

        <div class="grid grid-cols-2 gap-3">
            <input class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" type="number" step="0.01" x-model="formData.estimated_price_min" placeholder="Min price">
            <input class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" type="number" step="0.01" x-model="formData.estimated_price_max" placeholder="Max price">
        </div>

        <template x-if="formData.mode === 'booking'">
            <input class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" type="text" x-model="formData.preferred_time_window" placeholder="Preferred booking window, e.g. Weekdays 9am-1pm">
        </template>

        <template x-if="formData.mode === 'invoice'">
            <div class="grid grid-cols-2 gap-3">
                <input class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" type="text" x-model="formData.payment_terms" placeholder="Payment terms, e.g. Due on receipt">
                <input class="rounded-[10px] border border-transparent bg-foreground/5 px-6 py-4 text-sm font-medium" type="number" x-model="formData.due_days" placeholder="Due days">
            </div>
        </template>
    </div>

    <x-button class="mt-5 w-full bg-gradient-to-r from-gradient-from via-gradient-via to-gradient-to py-[18px] text-xs font-medium leading-none text-primary-foreground" @click.prevent="nextStep()">Continue <x-tabler-arrow-right class="size-4" /></x-button>
</div>
