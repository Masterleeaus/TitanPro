@extends('panel.layout.app', ['disable_tblr' => true])

@section('title', $surfaceName)
@section('titlebar_title', $surfaceName)
@section('titlebar_subtitle', __('Create quote visuals, reusable templates, and AI-assisted quote builders.'))

@section('content')
<div class="py-10">
    <div class="mb-5 flex flex-wrap gap-3">
        <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.builder') }}'">
            {{ __('Open Quote Builder Wizard') }}
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.templates') }}'">
            {{ __('Browse Templates') }}
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.gallery') }}'">
            {{ __('Open Drafts') }}
        </button>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <x-card variant="outline" size="lg">
                <form
                    id="quotemaker-form"
                    class="flex flex-col gap-5"
                    method="POST"
                    action="{{ route('dashboard.user.quotemaker.generate') }}"
                >
                    @csrf

                    <div class="row gap-y-5">
                        <div class="col-12 col-md-6">
                            <x-forms.input
                                id="service_type"
                                name="service_type"
                                size="lg"
                                label="{{ __('Service type') }}"
                                placeholder="{{ __('House Cleaning') }}"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-forms.input
                                id="site_type"
                                name="site_type"
                                size="lg"
                                label="{{ __('Site type') }}"
                                placeholder="{{ __('Residential Interior') }}"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-forms.input
                                id="work_area"
                                name="work_area"
                                size="lg"
                                label="{{ __('Work area') }}"
                                placeholder="{{ __('Kitchen, bathrooms, hallway') }}"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-forms.input
                                id="quote_reference"
                                name="quote_reference"
                                size="lg"
                                label="{{ __('Quote reference') }}"
                                placeholder="{{ __('Q-10042') }}"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-forms.input
                                id="visual_mode"
                                name="visual_mode"
                                size="lg"
                                type="select"
                                label="{{ __('Visual mode') }}"
                            >
                                @foreach($visualModes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </x-forms.input>
                        </div>

                        <div class="col-12 col-md-6">
                            <x-forms.input
                                id="package_tier"
                                name="package_tier"
                                size="lg"
                                type="select"
                                label="{{ __('Package tier') }}"
                            >
                                @foreach($packageTiers as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </x-forms.input>
                        </div>

                        <div class="col-12">
                            <x-forms.input
                                id="scope_notes"
                                name="scope_notes"
                                size="lg"
                                type="textarea"
                                label="{{ __('Scope notes') }}"
                                placeholder="{{ __('Describe the expected result and the service scope.') }}"
                            />
                        </div>

                        <div class="col-12">
                            <x-forms.input
                                id="customer_context"
                                name="customer_context"
                                size="lg"
                                type="textarea"
                                label="{{ __('Customer context') }}"
                                placeholder="{{ __('Optional customer context or constraints.') }}"
                            />
                        </div>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-3">
                        <x-button type="submit" size="lg">
                            {{ __('Prepare Quote Visual') }}
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-12 col-xl-4">
            <x-card variant="outline" size="md">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h3 class="mb-0">{{ __('Templates') }}</h3>
                    <button type="button" class="btn btn-link p-0" onclick="window.location.href='{{ route('dashboard.user.quotemaker.templates') }}'">
                        {{ __('View all') }}
                    </button>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach($templates as $template)
                        @include('productphotography::partials.template_card', ['template' => $template])
                    @endforeach
                </div>
            </x-card>

            <x-card variant="outline" size="md" class="mt-4">
                <h3 class="mb-3">{{ __('What this does') }}</h3>
                <ul class="mb-0 text-2xs text-foreground/60">
                    <li>{{ __('Turns service scope into visual quote drafts.') }}</li>
                    <li>{{ __('Lets you build reusable themed templates.') }}</li>
                    <li>{{ __('Supports an optional quote bot for follow-up logic.') }}</li>
                </ul>
            </x-card>
        </div>
    </div>
</div>
@endsection
