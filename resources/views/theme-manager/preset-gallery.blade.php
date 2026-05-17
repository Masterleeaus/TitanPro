@php($gallery = \App\Support\ThemePresetManager::gallery())

<x-filament::section>
    <x-slot name="heading">Theme Presets</x-slot>
    <x-slot name="description">Apply, duplicate, export, or manage custom presets.</x-slot>

    <div class="mb-4">
        <x-filament::button tag="a" href="{{ route('theme-presets.export') }}" size="sm" color="gray">Export Custom Presets</x-filament::button>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($gallery as $preset)
            <div class="rounded-xl border bg-white p-4 shadow-sm dark:bg-gray-900 {{ $preset['active'] ? 'ring-2 ring-primary-500' : '' }}">
                <div class="mb-3 h-24 rounded-lg border shadow-inner" style="{{ $preset['preview_css'] }}"></div>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold">{{ $preset['label'] }}</h3>
                        <p class="mt-1 text-xs opacity-70">{{ $preset['description'] ?? '' }}</p>
                    </div>
                    @if ($preset['active'])
                        <span class="rounded-full bg-primary-100 px-2 py-1 text-xs font-medium text-primary-700">Active</span>
                    @elseif ($preset['custom'] ?? false)
                        <span class="rounded-full bg-warning-100 px-2 py-1 text-xs font-medium text-warning-700">Custom</span>
                    @endif
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('theme-presets.apply') }}">@csrf<input type="hidden" name="preset" value="{{ $preset['slug'] }}"><x-filament::button type="submit" size="sm" :disabled="$preset['active']">{{ $preset['active'] ? 'Applied' : 'Apply' }}</x-filament::button></form>
                    <form method="POST" action="{{ route('theme-presets.duplicate') }}">@csrf<input type="hidden" name="preset" value="{{ $preset['slug'] }}"><x-filament::button type="submit" size="sm" color="gray">Duplicate</x-filament::button></form>
                    @if ($preset['custom'] ?? false)
                        <form method="POST" action="{{ route('theme-presets.delete') }}">@csrf<input type="hidden" name="preset" value="{{ $preset['slug'] }}"><x-filament::button type="submit" size="sm" color="danger">Delete</x-filament::button></form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>
