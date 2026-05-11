<div class="col-start-1 col-end-1 row-start-1 row-end-1 w-full transition duration-300" x-show="currentStep === 1" x-cloak>
    <h2 class="mb-5 text-center text-[24px] font-medium leading-[1.2em]">
        <span class="block text-[0.875em] opacity-50">Choose the flow mode</span>
        Is this for a quote, booking, or invoice?
    </h2>

    <div class="mb-4 grid grid-cols-3 gap-3">
        <template x-for="mode in modes" :key="mode">
            <button type="button" class="rounded-[12px] border border-foreground/10 bg-foreground/5 px-4 py-4 text-xs font-medium transition" :class="{ 'border-primary bg-primary/10': formData.mode === mode }" @click.prevent="formData.mode = mode">
                <span class="capitalize" x-text="mode"></span>
            </button>
        </template>
    </div>

    <div class="mb-4 grid grid-cols-2 gap-3">
        <template x-for="vertical in verticals" :key="vertical">
            <button type="button" class="rounded-[12px] border border-foreground/10 bg-foreground/5 px-4 py-4 text-xs font-medium transition" :class="{ 'border-primary bg-primary/10': formData.vertical === vertical }" @click.prevent="formData.vertical = vertical">
                <span x-text="vertical"></span>
            </button>
        </template>
    </div>

    <div class="mb-3 rounded-[10px] bg-foreground/5 px-6 py-3">
        <label class="mb-2 block text-2xs opacity-50">Service type</label>
        <input class="w-full border-none bg-transparent text-sm font-medium" type="text" x-model="formData.service_type" placeholder="House Cleaning, Garden Tidy, Driveway Pressure Wash">
    </div>

    <div class="rounded-[10px] bg-foreground/5 px-6 py-3">
        <label class="mb-2 block text-2xs opacity-50">Variation</label>
        <input class="w-full border-none bg-transparent text-sm font-medium" type="text" x-model="formData.variation" placeholder="Recurring, Premium, Vacate, Emergency">
    </div>

    @php($stepKey = 1)
    @include('productphotography::create.step-error', ['stepKey' => 1])

    <x-button class="mt-5 w-full bg-gradient-to-r from-gradient-from via-gradient-via to-gradient-to py-[18px] text-xs font-medium leading-none text-primary-foreground" @click.prevent="nextStep()">Continue <x-tabler-arrow-right class="size-4" /></x-button>
</div>
