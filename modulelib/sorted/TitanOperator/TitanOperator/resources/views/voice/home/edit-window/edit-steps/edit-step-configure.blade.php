{{-- Editing Step 1 - Configure --}}
<div
    class="col-start-1 col-end-1 row-start-1 row-end-1 transition-all"
    data-step="1"
    x-show="editingStep === 1"
    x-transition:enter-start="opacity-0 -translate-x-3"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-3"
>
    <h2 class="mb-3.5">
        @lang('Configure')
    </h2>
    <p class="text-xs/5 opacity-60 lg:max-w-[360px]">
        @lang('Create and configure a titan_operator that interacts with your users, ensuring it delivers accurate information.')
    </p>

    <div class="flex flex-col gap-7 pt-9">
        <div>
            <x-forms.input
                class:label="text-heading-foreground"
                label="{{ __('Titan Operator Title') }}"
                placeholder="{{ __('MagicBot') }}"
                name="title"
                size="lg"
                x-model="activeTitanOperator.title"
                @input.throttle.250ms="externalTitanOperator && externalTitanOperator.toggleWindowState('open')"
            />

            <template
                x-for="(error, index) in formErrors.title"
                :key="'error-' + index"
            >
                <div class="mt-2 text-2xs/5 font-medium text-red-500">
                    <p x-text="error"></p>
                </div>
            </template>
        </div>

        <div>
            <x-forms.input
                class:label="text-heading-foreground"
                label="{{ __('Bubble Message') }}"
                placeholder="{{ __('MagicBot') }}"
                name="bubble_message"
                size="lg"
                x-model="activeTitanOperator.bubble_message"
                @input.throttle.250ms="externalTitanOperator && externalTitanOperator.toggleWindowState('close')"
            />

            <template
                x-for="(error, index) in formErrors.bubble_message"
                :key="'error-' + index"
            >
                <div class="mt-2 text-2xs/5 font-medium text-red-500">
                    <p x-text="error"></p>
                </div>
            </template>
        </div>

        <div>
            <x-forms.input
                class:label="text-heading-foreground"
                label="{{ __('Welcome Message') }}"
                placeholder="{{ __('Enter welcome message') }}"
                name="welcome_message"
                size="lg"
                x-model="activeTitanOperator.welcome_message"
                @input.throttle.250ms="if ( externalTitanOperator ) { externalTitanOperator.toggleWindowState('open'); externalTitanOperator.toggleView('conversation-messages') }"
            />

            <template
                x-for="(error, index) in formErrors.welcome_message"
                :key="'error-' + index"
            >
                <div class="mt-2 text-2xs/5 font-medium text-red-500">
                    <p x-text="error"></p>
                </div>
            </template>
        </div>

        <div>
            <x-forms.input
                class:label="text-heading-foreground"
                label="{{ __('Titan Operator Instructions') }}"
                placeholder="{{ __('Explain titan_operator role') }}"
                name="instructions"
                size="lg"
                type="textarea"
                rows="5"
                x-model="activeTitanOperator.instructions"
            />

            <template
                x-for="(error, index) in formErrors.instructions"
                :key="'error-' + index"
            >
                <div class="mt-2 text-2xs/5 font-medium text-red-500">
                    <p x-text="error"></p>
                </div>
            </template>
        </div>

        <div>
            <x-forms.input
                class:label="text-heading-foreground"
                label="{{ __('Language') }}"
                name="language"
                size="lg"
                type="select"
                x-model="activeTitanOperator.language"
            >
                <option
                    value="en"
                    selected
                >
                    @lang('Auto')
                </option>
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if (in_array($localeCode, explode(',', $settings_two->languages), true))
                        <option value="{{ $localeCode }}">
                            {{ $properties['name'] }}
                        </option>
                    @endif
                @endforeach
            </x-forms.input>
            <template
                x-for="(error, index) in formErrors.language"
                :key="'error-' + index"
            >
                <div class="mt-2 text-2xs/5 font-medium text-red-500">
                    <p x-text="error"></p>
                </div>
            </template>
        </div>
    </div>
</div>
