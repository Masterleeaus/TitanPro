<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Super Admin Links</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Canonical admin links. Old platform routes are intentionally not used here.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->panels as $panel)
                <a href="{{ url($panel['url']) }}" class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-primary-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-950 group-hover:text-primary-600 dark:text-white">{{ $panel['label'] }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $panel['description'] }}</p>
                            <p class="mt-3 text-xs font-mono text-gray-500 dark:text-gray-500">{{ url($panel['url']) }}</p>
                            @if (! empty($panel['roles']))
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">Roles: {{ $panel['roles'] }}</p>
                            @endif
                        </div>
                        <span class="rounded-full bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 dark:bg-primary-500/10 dark:text-primary-300">{{ $panel['badge'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
