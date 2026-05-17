<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Super Admin Feature Health Audit</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Checks that core Super Admin features are available, professionally scoped, and isolated inside the /admin control panel.
            </p>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Check</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Target</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($this->checks as $check)
                        <tr>
                            <td class="px-4 py-3 text-gray-950 dark:text-white">{{ $check['label'] }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $check['target'] }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-medium {{ $check['ok'] ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-300' }}">
                                    {{ $check['ok'] ? 'OK' : 'Missing' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
