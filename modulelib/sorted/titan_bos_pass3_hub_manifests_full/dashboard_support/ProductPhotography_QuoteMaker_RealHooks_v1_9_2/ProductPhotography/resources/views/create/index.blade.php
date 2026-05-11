@extends('panel.layout.app', ['disable_tblr' => true, 'disable_titlebar' => true])

@section('title', $surfaceName . ' Builder')

@push('css')
<style>
    @media (min-width: 992px) {
        .lqd-navbar-expander,
        .lqd-navbar,
        .lqd-header,
        .lqd-page-footer {
            display: none !important;
        }
        .lqd-page-wrapper {
            padding-inline: 0 !important;
        }
    }

    .qm-affiliate-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        border: 1px solid rgba(255,255,255,.08);
        background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
        box-shadow: 0 12px 40px rgba(0,0,0,.18);
        backdrop-filter: blur(12px);
    }

    .qm-affiliate-icon {
        display: inline-flex;
        width: 72px;
        height: 72px;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: color-mix(in oklab, hsl(var(--accent)) 10%, transparent);
    }

    .qm-affiliate-shell {
        padding: 28px;
    }

    @media (min-width: 992px) {
        .qm-affiliate-shell {
            padding: 40px;
        }
    }
</style>
@endpush

@section('content')
@php $total_steps = 7; @endphp
<div
    class="flex min-h-screen flex-col items-center justify-center py-10 lg:py-20"
    x-data="quoteWizard"
    style="--current-step: 0; --total-steps: {{ $total_steps }}"
    :style="{ '--current-step': currentStep, '--total-steps': totalSteps }"
>
    <div class="absolute inset-x-0 top-[--header-height] z-5 flex items-center p-4 lg:fixed lg:top-0">
        <div class="flex basis-1/3">
            <div x-cloak x-show="currentStep > 0" x-transition>
                <x-button variant="link" @click.prevent="prevStep()">
                    <x-tabler-arrow-left class="size-4" />
                    @lang('Back')
                </x-button>
            </div>
        </div>

        <div class="flex basis-1/3 justify-center">
            <div class="relative h-[5px] w-40 overflow-hidden rounded-full bg-foreground/5 backdrop-blur-lg">
                <div class="absolute inset-y-0 start-0 w-full origin-left scale-x-[calc(var(--current-step)/var(--total-steps))] rounded-full bg-gradient-to-r from-gradient-from via-gradient-via to-gradient-to transition rtl:origin-right"></div>
            </div>
        </div>

        <div class="flex basis-1/3 items-center justify-end gap-9">
            <p class="m-0 text-xs font-medium" x-show="currentStep > 0 && currentStep <= 7" x-transition x-cloak>
                @lang('Step') <span x-text="Math.max(1,currentStep)">1</span> @lang('of') {{ $total_steps }}
            </p>
            <button
                type="button"
                class="hidden size-9 place-items-center rounded-full bg-background text-foreground shadow-xs lg:inline-grid"
                onclick="window.location.href='{{ route('dashboard.user.quotemaker.index') }}'"
                title="{{ __('Close') }}"
            >
                <x-tabler-x class="size-4" />
            </button>
        </div>
    </div>

    <div class="container px-4 lg:px-0">
        <div class="mx-auto lg:max-w-[760px]">
            <div class="qm-affiliate-card">
                <div class="qm-affiliate-shell">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="qm-affiliate-icon">
                            <svg width="32" height="32" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M26.4595 16.7999H1.65428V24.3596C1.65524 25.4733 2.09117 26.5411 2.86639 27.3286C3.64161 28.1162 4.69276 28.559 5.78908 28.56H22.3247C23.421 28.559 24.4721 28.1162 25.2474 27.3286C26.0226 26.5411 26.4585 25.4733 26.4595 24.3596V16.7999ZM11.5764 26.0401C11.5764 26.263 11.4892 26.4767 11.3341 26.6343C11.179 26.7918 10.9686 26.8804 10.7492 26.8804C10.5298 26.8804 10.3195 26.7918 10.1643 26.6343C10.0092 26.4767 9.92208 26.263 9.92208 26.0401V19.3198C9.92208 19.0969 10.0092 18.8832 10.1643 18.7256C10.3195 18.568 10.5298 18.4795 10.7492 18.4795C10.9686 18.4795 11.179 18.568 11.3341 18.7256C11.4892 18.8832 11.5764 19.0969 11.5764 19.3198V26.0401ZM18.1917 26.0401C18.1917 26.263 18.1045 26.4767 17.9494 26.6343C17.7943 26.7918 17.5839 26.8804 17.3645 26.8804C17.1452 26.8804 16.9348 26.7918 16.7797 26.6343C16.6245 26.4767 16.5374 26.263 16.5374 26.0401V19.3198C16.5374 19.0969 16.6245 18.8832 16.7797 18.7256C16.9348 18.568 17.1452 18.4795 17.3645 18.4795C17.5839 18.4795 17.7943 18.568 17.9494 18.7256C18.1045 18.8832 18.1917 19.0969 18.1917 19.3198V26.0401Z" fill="hsl(var(--accent))"/>
                            </svg>
                        </div>

                        <div>
                            <p class="mb-1 text-xs uppercase tracking-wide text-foreground/50">{{ __('QuoteMaker Wizard') }}</p>
                            <h1 class="mb-0 text-2xl font-semibold text-heading-foreground">{{ $surfaceName }}</h1>
                            <p class="mt-2 text-sm text-foreground/60">
                                {{ __('Build a reusable quote template, attach an optional quote bot, and prepare automated follow-up logic.') }}
                            </p>
                        </div>
                    </div>

                    <form class="grid w-full grid-cols-1 place-items-center" @submit.prevent="submitForm" @keydown.enter.prevent novalidate>
                        @include('productphotography::create.create-step-0')
                        @include('productphotography::create.create-step-1')
                        @include('productphotography::create.create-step-2')
                        @include('productphotography::create.create-step-3')
                        @include('productphotography::create.create-step-4')
                        @include('productphotography::create.create-step-5')
                        @include('productphotography::create.create-step-6')
                        @include('productphotography::create.create-step-7')
                        @include('productphotography::create.create-step-8')
                        @include('productphotography::create.create-step-9')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quoteWizard', () => ({
        totalSteps: 7,
        currentStep: 0,
        submitting: false,
        stepsErrors: new Map(),
        successRedirect: '{{ route('dashboard.user.quotemaker.templates') }}',

        verticals: ['Cleaning', 'Gardening', 'Pressure Washing', 'Handyman', 'Painting', 'Moving / Rubbish', 'Commercial'],
        themes: ['Clean Professional', 'Premium Home Service', 'Fast Local Offer', 'Commercial Scope', 'Before/After Heavy', 'Insurance / Compliance Style'],
        visualModes: ['quote_preview', 'before_after', 'proposal_cover', 'scope_visual'],
        packageTiers: ['basic', 'standard', 'premium'],
        followUpChannels: ['email', 'sms', 'email_sms', 'phone'],

        modes: ['quote', 'booking', 'invoice'],

        formData: {
            mode: 'quote',
            name: '',
            vertical: '',
            service_type: '',
            variation: 'Standard',
            theme: 'Clean Professional',
            visual_mode: 'quote_preview',
            package_tier: 'standard',
            site_type: '',
            scope_notes: '',
            pricing_model: 'fixed_scope',
            estimated_hours: '',
            estimated_price_min: '',
            estimated_price_max: '',
            quote_ai_enabled: true,
            ai_questions: [
                'How many rooms or zones are included?',
                'Is access easy or restricted?',
                'Are there special stains, damage, or heavy build-up?'
            ],
            attach_quote_bot: true,
            quote_bot_name: '',
            follow_up_delay_hours: 24,
            follow_up_channel: 'email_sms',
            auto_create_booking: false,
            auto_create_job: false,
            auto_negotiate: false,
            negotiation_floor_percent: 10,
            negotiation_notes: '',
        },

        init() {
            for (let i = 0; i <= this.totalSteps; i++) this.stepsErrors.set(i, []);
        },

        validateStep() {
            this.stepsErrors.set(this.currentStep, []);
            const e = this.stepsErrors.get(this.currentStep);

            if (this.currentStep === 1) {
                if (!this.formData.vertical) e.push('Please select a service vertical.');
                if (!this.formData.service_type.trim()) e.push('Please define the service type.');
            }

            if (this.currentStep === 2) {
                if (!this.formData.site_type.trim()) e.push('Please describe the property or site type.');
                if (!this.formData.scope_notes.trim()) e.push('Please add some scope notes.');
            }

            if (this.currentStep === 3 && !this.formData.pricing_model.trim()) {
                e.push('Please choose a pricing model.');
            }

            if (this.currentStep === 5 && this.formData.attach_quote_bot && !this.formData.quote_bot_name.trim()) {
                e.push('Please name the quote bot or disable it.');
            }

            if (this.currentStep === 7 && !this.formData.name.trim()) {
                e.push('Please name this quote builder.');
            }
        },

        nextStep() {
            this.validateStep();
            if (!this.stepsErrors.get(this.currentStep).length && this.currentStep < 7) this.currentStep++;
        },

        prevStep() {
            if (this.currentStep > 0) this.currentStep--;
        },

        async submitForm() {
            this.validateStep();
            if (this.stepsErrors.get(this.currentStep).length) return;

            this.currentStep = 8;

            try {
                const response = await fetch('{{ route('dashboard.user.quotemaker.builder.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Could not save quote builder.');
                }

                this.successRedirect = data.redirect_url || '{{ route('dashboard.user.quotemaker.templates') }}';
                this.currentStep = 9;
            } catch (error) {
                console.error(error);
                alert(error.message || 'Could not save quote builder.');
                this.currentStep = 7;
            }
        }
    }));
});
</script>
@endpush
