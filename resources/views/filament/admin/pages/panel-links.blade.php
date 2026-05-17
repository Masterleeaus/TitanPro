<x-filament-panels::page>
    <div class="space-y-8">
        <section>
            <h2 class="text-xl font-semibold tracking-tight">Panels</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($this->getPanels() as $panel)
                    <a href="{{ $panel['url'] }}" class="block rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $panel['label'] }}</div>
                                @if ($panel['description'])
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $panel['description'] }}</p>
                                @endif
                            </div>
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ $panel['badge'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold tracking-tight">Super Admin Tools</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($this->getAdminTools() as $tool)
                    <a href="{{ $tool['url'] }}" class="block rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow dark:border-gray-800 dark:bg-gray-900">
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $tool['label'] }}</div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $tool['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-filament-panels::page>
