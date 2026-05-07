<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-start gap-3">
            <x-filament::button type="submit">
                Save Stripe Settings
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
