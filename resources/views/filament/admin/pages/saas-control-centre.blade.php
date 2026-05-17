<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Super Admin Feature Scan</x-slot>
            <x-slot name="description">
                Professional conversion map for the uploaded Superadmin module. This keeps the Admin panel focused on platform governance rather than legacy operational screens.
            </x-slot>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($this->featureCards as $card)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ $card['label'] }}</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $card['description'] }}</p>
                            </div>
                            <div class="rounded-lg bg-primary-50 px-3 py-2 text-right dark:bg-primary-950">
                                <div class="text-xl font-bold text-primary-700 dark:text-primary-300">{{ $card['metric'] }}</div>
                                <div class="text-xs text-primary-600 dark:text-primary-400">records</div>
                            </div>
                        </div>
                        <div class="mt-4 rounded-lg bg-gray-50 p-3 text-sm dark:bg-gray-950">
                            <div class="font-medium text-gray-900 dark:text-gray-100">Status: {{ $card['status'] }}</div>
                            <div class="mt-1 text-gray-600 dark:text-gray-400">{{ $card['intent'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Upgrade Roadmap</x-slot>
            <div class="grid gap-4 lg:grid-cols-2">
                @foreach ($this->upgradePhases as $phase => $items)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="font-semibold text-gray-950 dark:text-white">{{ $phase }}</h3>
                        <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-gray-600 dark:text-gray-400">
                            @foreach ($items as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Implementation Notes</x-slot>
            <ul class="list-disc space-y-2 pl-5 text-sm text-gray-600 dark:text-gray-400">
                @foreach ($this->riskNotes as $note)
                    <li>{{ $note }}</li>
                @endforeach
            </ul>
        </x-filament::section>
    </div>
</x-filament-panels::page>
