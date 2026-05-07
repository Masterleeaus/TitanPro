<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        @if ($this->generatedThemePreview)
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->generatedThemePreview['name'] }}</p>
                <div class="mt-3 grid gap-3 md:grid-cols-3">
                    <div class="rounded-lg border p-3 text-xs">
                        <p class="text-gray-500">Primary</p>
                        <div class="mt-2 h-8 rounded" style="background: {{ e($this->generatedThemePreview['primary_color']) }}"></div>
                        <p class="mt-1">{{ $this->generatedThemePreview['primary_color'] }}</p>
                    </div>
                    <div class="rounded-lg border p-3 text-xs">
                        <p class="text-gray-500">Secondary</p>
                        <div class="mt-2 h-8 rounded" style="background: {{ e($this->generatedThemePreview['secondary_color']) }}"></div>
                        <p class="mt-1">{{ $this->generatedThemePreview['secondary_color'] }}</p>
                    </div>
                    <div class="rounded-lg border p-3 text-xs">
                        <p class="text-gray-500">Surface</p>
                        <div class="mt-2 h-8 rounded" style="background: {{ e($this->generatedThemePreview['surface_color']) }}"></div>
                        <p class="mt-1">{{ $this->generatedThemePreview['surface_color'] }}</p>
                    </div>
                </div>
                @if (! empty($this->generatedThemePreview['bg_image_url']))
                    <div class="mt-3 h-28 rounded-lg border bg-cover bg-center" style="background-image: url('{{ e($this->generatedThemePreview['bg_image_url']) }}')"></div>
                @endif
                @if (! empty($this->generatedThemePreview['wcag_warning']))
                    <div class="mt-3 rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm text-amber-800">
                        {{ $this->generatedThemePreview['wcag_warning'] }}
                    </div>
                @endif
            </div>
        @endif

        <x-filament::button type="submit">
            Save branding settings
        </x-filament::button>
    </form>
</x-filament-panels::page>
