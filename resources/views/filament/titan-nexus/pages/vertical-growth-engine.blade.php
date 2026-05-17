@php
    $engine = app(\Modules\TitanNexus\Services\VerticalGrowthEngine::class);
    $stages = $engine->stages();
    $verticals = $engine->verticals();
    $automationMap = $engine->automationMap();
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section
            heading="Vertical Growth Engine"
            description="Find niche leads, contact them, send offers, follow up, book work, train the team, and generate paperwork."
        >
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($stages as $stage)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
                        <div class="text-xs font-semibold uppercase tracking-wide text-primary-600 dark:text-primary-400">
                            {{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }} · {{ $stage['key'] }}
                        </div>
                        <h3 class="mt-2 text-base font-semibold text-gray-950 dark:text-white">{{ $stage['label'] }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $stage['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section
            heading="Starter vertical packs"
            description="These are the first practical verticals wired from the MarketingBot base concepts."
        >
            <div class="space-y-4">
                @foreach ($verticals as $vertical)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-white">{{ $vertical['name'] }}</h3>
                        <div class="mt-4 grid gap-4 lg:grid-cols-4">
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-500">Buyers</div>
                                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm text-gray-600 dark:text-gray-300">
                                    @foreach ($vertical['buyers'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-500">Lead sources</div>
                                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm text-gray-600 dark:text-gray-300">
                                    @foreach ($vertical['lead_sources'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-500">Training</div>
                                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm text-gray-600 dark:text-gray-300">
                                    @foreach ($vertical['training'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-500">Paperwork</div>
                                <ul class="mt-2 list-disc space-y-1 pl-4 text-sm text-gray-600 dark:text-gray-300">
                                    @foreach ($vertical['paperwork'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <p class="mt-4 rounded-lg bg-primary-50 p-3 text-sm text-primary-800 dark:bg-primary-500/10 dark:text-primary-200">
                            Offer: {{ $vertical['offer'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section
            heading="MarketingBot base mapping"
            description="MarketingBot has been added as a TitanNexus reference base and mapped into the live growth workflow."
        >
            <dl class="grid gap-3 md:grid-cols-2">
                @foreach ($automationMap as $source => $target)
                    <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
                        <dt class="font-semibold text-gray-950 dark:text-white">{{ $source }}</dt>
                        <dd class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $target }}</dd>
                    </div>
                @endforeach
            </dl>
        </x-filament::section>
    </div>
</x-filament-panels::page>
