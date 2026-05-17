<x-filament-panels::page>
    @php
        $config = $this->pageConfig();
        $cards = $this->dashboardCards();
        $table = $config['table'] ?? null;
        $columns = $config['columns'] ?? ['id', 'name', 'status', 'created_at'];
        $rows = $table ? $this->rowsFor($table, $columns) : [];
        $installedTables = $this->installedMarketingTables();
    @endphp

    <div class="space-y-6">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <p class="text-xs font-semibold uppercase tracking-wide text-primary-600 dark:text-primary-400">{{ $config['kicker'] ?? 'TitanNexus' }}</p>
            <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $config['heading'] ?? static::getTitle() }}</h2>
                    <p class="mt-2 max-w-4xl text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $config['description'] ?? '' }}</p>
                </div>
                @if (! empty($config['actions']))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($config['actions'] as $action)
                            <a href="{{ $action['url'] }}" class="inline-flex items-center rounded-xl bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                                {{ $action['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($cards as $card)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</div>
                    <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ number_format($card['value']) }}</div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $card['hint'] }}</div>
                </div>
            @endforeach
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-950 dark:text-white">{{ $table ? str($table)->replace('_', ' ')->title() : 'MarketingBot data' }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Live table preview. Empty tables are ready for campaigns, contacts and automation data.</p>
                </div>
                @if ($table)
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">{{ $table }}</span>
                @endif
            </div>

            @if ($table && count($rows) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-white/10">
                        <thead>
                            <tr>
                                @foreach (array_keys($rows[0]) as $column)
                                    <th class="whitespace-nowrap px-3 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">{{ str($column)->replace('_', ' ')->title() }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @foreach ($rows as $row)
                                <tr>
                                    @foreach ($row as $value)
                                        <td class="max-w-xs truncate px-3 py-2 text-gray-700 dark:text-gray-200">{{ is_scalar($value) || $value === null ? ($value ?? '—') : json_encode($value) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-600 dark:border-white/10 dark:text-gray-300">
                    No rows yet. The TitanNexus menu is integrated; run campaigns/imports to populate this area.
                </div>
            @endif
        </section>

        @if (! empty($config['secondary_tables']))
            <section class="grid gap-4 md:grid-cols-2">
                @foreach ($config['secondary_tables'] as $secondaryTable)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ str($secondaryTable)->replace('_', ' ')->title() }}</div>
                        <div class="mt-2 text-2xl font-bold text-primary-600 dark:text-primary-400">{{ number_format($this->countRows($secondaryTable)) }}</div>
                        <div class="mt-1 text-xs text-gray-500">{{ $secondaryTable }}</div>
                    </div>
                @endforeach
            </section>
        @endif

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">MarketingBot integration status</h3>
            <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($installedTables as $status)
                    <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-white/10">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $status['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ number_format($status['rows']) }} rows</div>
                        </div>
                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $status['ready'] ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300' }}">
                            {{ $status['ready'] ? 'ready' : 'missing' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-filament-panels::page>
