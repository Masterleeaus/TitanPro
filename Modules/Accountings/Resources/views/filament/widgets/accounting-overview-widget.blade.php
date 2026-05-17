<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-2">
            <h3 class="text-sm font-semibold">Revenue, Expenses &amp; Net P&amp;L</h3>
            <div class="grid gap-2">
                @foreach(($metrics ?? []) as $metric)
                    <div class="text-sm text-gray-700">{{ $metric }}</div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
