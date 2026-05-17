<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-semibold tracking-tight">Titan Pro Feature Map</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                This map keeps Titan Pro focused on business operations while Super Admin remains responsible for governance, modules, packages, roles, themes, and platform repair.
            </p>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($this->featureGroups as $group => $payload)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="font-semibold">{{ $group }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $payload['intent'] }}</p>
                    <div class="mt-4 space-y-2">
                        @foreach ($payload['classes'] as $resource)
                            <div class="flex items-center justify-between gap-3 rounded-lg bg-gray-50 px-3 py-2 text-xs dark:bg-gray-800">
                                <span class="truncate">{{ $resource['class'] }}</span>
                                <span class="font-medium {{ $resource['available'] ? 'text-success-600' : 'text-gray-400' }}">
                                    {{ $resource['available'] ? 'Available' : 'Missing' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="font-semibold">Upgrade Backlog</h3>
            <ul class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                @foreach ($this->upgradeBacklog as $item)
                    <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-primary-500"></span><span>{{ $item }}</span></li>
                @endforeach
            </ul>
        </div>
    </div>
</x-filament-panels::page>
