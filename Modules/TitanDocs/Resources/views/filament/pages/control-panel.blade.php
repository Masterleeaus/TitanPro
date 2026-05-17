<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-3">
        <x-filament::section>
            <x-slot name="heading">{{ __('Start document wizard') }}</x-slot>
            <x-slot name="description">{{ __('Launch the multi-step wizard to assemble a document or SWMS with structured context.') }}</x-slot>

            <x-filament::button tag="a" :href="route('titan.docs.generator.start')">
                {{ __('Open wizard') }}
            </x-filament::button>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">{{ __('Browse templates') }}</x-slot>
            <x-slot name="description">{{ __('Open the TitanDocs template library and generate a document from an approved prompt template.') }}</x-slot>

            <x-filament::button tag="a" :href="route('titan.docs.index')" color="gray">
                {{ __('Open library') }}
            </x-filament::button>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">{{ __('View history') }}</x-slot>
            <x-slot name="description">{{ __('Review generated documents, SWMS runs, and saved history records.') }}</x-slot>

            <x-filament::button tag="a" :href="route('titan.docs.history')" color="gray">
                {{ __('Open history') }}
            </x-filament::button>
        </x-filament::section>
    </div>
</x-filament-panels::page>
