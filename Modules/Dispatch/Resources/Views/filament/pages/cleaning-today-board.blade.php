<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
        @foreach ($lanes as $lane)
            <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-sm font-semibold">{{ $lane['lane'] }}</h2>
                    <span class="text-xs text-gray-500">{{ $lane['count'] }} visits</span>
                </div>

                <div class="space-y-3">
                    @forelse ($lane['cards'] as $card)
                        <article class="rounded-lg border p-3 {{ $card['isLate'] ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                            <div class="font-medium">{{ $card['title'] }}</div>
                            <div class="text-xs text-gray-500">{{ $card['startsAt'] }} → {{ $card['endsAt'] }}</div>
                            <div class="mt-1 text-xs">{{ $card['location'] }}</div>
                            <div class="mt-2 text-xs font-semibold uppercase">{{ str_replace('_', ' ', $card['status']) }}</div>
                        </article>
                    @empty
                        <p class="text-sm text-gray-500">No cleaning visits assigned.</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</x-filament-panels::page>
