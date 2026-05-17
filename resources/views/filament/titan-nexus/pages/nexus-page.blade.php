<x-filament-panels::page>
    @php
        $config = $this->pageConfig();
        $table = $config['table'] ?? null;
        $columns = $config['columns'] ?? [];
        $rows = $table ? $this->rowsFor($table, $columns) : [];
    @endphp

    <div class="space-y-6">
        <x-filament::section>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-primary-600 dark:text-primary-400">{{ $config['kicker'] ?? 'TitanNexus' }}</p>
                    <h2 class="mt-2 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $config['heading'] ?? static::getTitle() }}</h2>
                    <p class="mt-2 max-w-5xl text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $config['description'] ?? '' }}</p>
                </div>

                @if (! empty($config['instructions']))
                    <div class="grid gap-3 md:grid-cols-3">
                        @foreach ($config['instructions'] as $instruction)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-white/10 dark:bg-white/5">
                                <p class="font-semibold text-gray-950 dark:text-white">{{ $instruction['title'] }}</p>
                                <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $instruction['body'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-filament::section>

        @if ($table)
            <x-filament::section heading="Records" description="Current records available for this workflow area.">
                @if (! $this->tableExists($table))
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-200">
                        Data table pending: {{ $table }}.
                    </div>
                @elseif ($rows === [])
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 dark:border-white/10 dark:bg-white/5 dark:text-gray-300">
                        No records are available for this workflow area.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-white/10">
                            <thead>
                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    @foreach (array_keys($rows[0]) as $column)
                                        <th class="px-3 py-2">{{ str_replace('_', ' ', $column) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                                @foreach ($rows as $row)
                                    <tr class="text-gray-700 dark:text-gray-200">
                                        @foreach ($row as $value)
                                            <td class="max-w-xs truncate px-3 py-2">{{ is_scalar($value) || $value === null ? $value : json_encode($value) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
