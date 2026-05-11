<x-card class="w-full mb-6" id="customer-wizard-shell" size="lg">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                <x-tabler-users class="size-4" />
                <span>{{ __('Customer Wizard') }}</span>
            </div>
            <h3 class="mb-1 text-[17px] leading-6">{{ __('Create customers, leads, campaigns, SMS and call tasks') }}</h3>
            <p class="max-w-2xl text-sm text-foreground/70">{{ __('This keeps the existing wizard shell, but reframes it for customer onboarding, outreach and campaign setup.') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-button variant="outline">{{ __('Add Customer') }}</x-button>
            <x-button variant="outline">{{ __('Add Lead') }}</x-button>
            <x-button>{{ __('Create Campaign') }}</x-button>
        </div>
    </div>
    <div class="mt-5 grid gap-3 md:grid-cols-3">
        <div class="rounded-xl border border-border bg-background/40 p-4">
            <div class="text-xs uppercase tracking-wide text-foreground/60">{{ __('Step 1') }}</div>
            <div class="mt-2 font-semibold">{{ __('Customer Details') }}</div>
            <div class="mt-1 text-xs text-foreground/70">{{ __('Name, company, lifecycle stage, tags') }}</div>
        </div>
        <div class="rounded-xl border border-border bg-background/40 p-4">
            <div class="text-xs uppercase tracking-wide text-foreground/60">{{ __('Step 2') }}</div>
            <div class="mt-2 font-semibold">{{ __('Contact & Channel') }}</div>
            <div class="mt-1 text-xs text-foreground/70">{{ __('Phone, email, SMS, call preferences') }}</div>
        </div>
        <div class="rounded-xl border border-border bg-background/40 p-4">
            <div class="text-xs uppercase tracking-wide text-foreground/60">{{ __('Step 3') }}</div>
            <div class="mt-2 font-semibold">{{ __('Campaign / Follow-up') }}</div>
            <div class="mt-1 text-xs text-foreground/70">{{ __('Owner, campaign, call queue, next action') }}</div>
        </div>
    </div>
</x-card>
