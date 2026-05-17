<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">TitanGo PWA Feature Command Center</x-slot>
        <x-slot name="description">Original field PWA capabilities surfaced inside the TitanGo panel for admin control, QA and rollout checks.</x-slot>

        <div class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="text-xs uppercase tracking-wide text-gray-500">App</div>
                    <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ $manifest['name'] }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $manifest['display'] }} · {{ $manifest['icons'] }} icons</div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Start URL</div>
                    <div class="mt-1 break-all text-sm font-semibold text-gray-950 dark:text-white">{{ $manifest['start_url'] }}</div>
                    <div class="mt-1 text-xs text-gray-500">Scope: {{ $manifest['scope'] }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Offline Reads</div>
                    <div class="mt-1 text-3xl font-bold text-gray-950 dark:text-white">{{ count($cached_reads) }}</div>
                    <div class="mt-1 text-xs text-gray-500">Jobs + catalog caches</div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Queued Writes</div>
                    <div class="mt-1 text-3xl font-bold text-gray-950 dark:text-white">{{ count($sync_routes) }}</div>
                    <div class="mt-1 text-xs text-gray-500">Background sync endpoints</div>
                </div>
            </div>

            <div class="grid gap-4 xl:grid-cols-[1.2fr_.8fr]">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-950 dark:border-gray-700 dark:text-white">Transferred PWA Features</div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($features as $feature)
                            <div class="grid gap-2 px-4 py-3 md:grid-cols-[1fr_auto_2fr] md:items-center">
                                <div class="font-medium text-gray-950 dark:text-white">{{ $feature['name'] }}</div>
                                <span @class([
                                    'w-max rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => $feature['status'] === 'Live',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' => $feature['status'] !== 'Live',
                                ])>{{ $feature['status'] }}</span>
                                <div class="text-sm text-gray-500">{{ $feature['detail'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="font-semibold text-gray-950 dark:text-white">Panel Links</div>
                        <div class="mt-3 grid gap-2">
                            @foreach ($links as $link)
                                <a href="{{ $link['url'] }}" target="_blank" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="font-semibold text-gray-950 dark:text-white">Module PWA Manifests</div>
                        <div class="mt-3 space-y-3">
                            @forelse ($module_manifests as $moduleManifest)
                                <div class="rounded-lg bg-gray-50 p-3 text-sm dark:bg-gray-800/60">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-medium text-gray-950 dark:text-white">{{ $moduleManifest['module'] }}</span>
                                        <span @class([
                                            'rounded-full px-2 py-0.5 text-xs font-medium',
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => $moduleManifest['enabled'],
                                            'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' => ! $moduleManifest['enabled'],
                                        ])>{{ $moduleManifest['enabled'] ? 'Enabled' : 'Disabled' }}</span>
                                    </div>
                                    @if (! empty($moduleManifest['capabilities']))
                                        <div class="mt-2 text-xs text-gray-500">{{ implode(', ', $moduleManifest['capabilities']) }}</div>
                                    @endif
                                    <div class="mt-1 truncate text-[11px] text-gray-400">{{ $moduleManifest['path'] }}</div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500">No module PWA manifests found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 xl:grid-cols-2">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-950 dark:border-gray-700 dark:text-white">Cached Read Routes</div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($cached_reads as $read)
                            <div class="grid gap-1 px-4 py-3 text-sm md:grid-cols-[1fr_auto]">
                                <div><span class="font-mono text-xs">{{ $read['route'] }}</span></div>
                                <div class="text-gray-500">{{ $read['cache'] }} · {{ $read['ttl'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 px-4 py-3 font-semibold text-gray-950 dark:border-gray-700 dark:text-white">Background Sync Writes</div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($sync_routes as $route)
                            <div class="grid gap-1 px-4 py-3 text-sm md:grid-cols-[auto_1fr_auto] md:items-center">
                                <span class="rounded bg-gray-100 px-2 py-1 font-mono text-xs dark:bg-gray-800">{{ $route['method'] }}</span>
                                <span class="break-all font-mono text-xs">{{ $route['route'] }}</span>
                                <span class="text-gray-500">{{ $route['feature'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
