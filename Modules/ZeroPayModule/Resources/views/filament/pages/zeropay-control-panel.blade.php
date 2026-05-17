<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Settings Form --}}
        <form wire:submit.prevent="save">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" icon="heroicon-o-check">
                    Save Settings
                </x-filament::button>
            </div>
        </form>

        {{-- Module Health --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                Module Health
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                {{-- Webhook queue depth --}}
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Webhook Queue Depth
                    </p>
                    <p class="mt-1 text-2xl font-bold {{ $this->webhookQueueDepth > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ $this->webhookQueueDepth }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">zeropay_webhook_events unprocessed</p>
                </div>

                {{-- Pending review deposits --}}
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Pending Review Deposits
                    </p>
                    <p class="mt-1 text-2xl font-bold {{ $this->pendingReviewCount > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ $this->pendingReviewCount }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">zeropay_bank_deposits pending_review</p>
                </div>

                {{-- Recent errors --}}
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Recent Gateway Errors
                    </p>
                    <p class="mt-1 text-2xl font-bold {{ count($this->recentErrors) > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ count($this->recentErrors) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">4xx/5xx in last 10 log entries</p>
                </div>

                {{-- Last gateway checks --}}
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Active Gateways Seen
                    </p>
                    <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ count($this->lastGatewayChecks) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">gateways with recent log activity</p>
                </div>
            </div>

            @if (count($this->lastGatewayChecks) > 0)
                <div class="mt-4">
                    <h3 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Last Gateway Activity</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="py-2 pr-4 text-left font-medium text-gray-500 dark:text-gray-400">Gateway</th>
                                    <th class="py-2 text-left font-medium text-gray-500 dark:text-gray-400">Last Checked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->lastGatewayChecks as $gateway => $timestamp)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pr-4 font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst(str_replace('_', ' ', $gateway)) }}
                                        </td>
                                        <td class="py-2 text-gray-500 dark:text-gray-400">
                                            {{ $timestamp ? \Carbon\Carbon::parse($timestamp)->diffForHumans() : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if (count($this->recentErrors) > 0)
                <div class="mt-4">
                    <h3 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Recent Errors</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="py-2 pr-4 text-left font-medium text-gray-500 dark:text-gray-400">Gateway</th>
                                    <th class="py-2 pr-4 text-left font-medium text-gray-500 dark:text-gray-400">Event</th>
                                    <th class="py-2 pr-4 text-left font-medium text-gray-500 dark:text-gray-400">HTTP Status</th>
                                    <th class="py-2 text-left font-medium text-gray-500 dark:text-gray-400">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->recentErrors as $error)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pr-4 font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst(str_replace('_', ' ', $error['gateway'] ?? '—')) }}
                                        </td>
                                        <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $error['event'] ?? '—' }}
                                        </td>
                                        <td class="py-2 pr-4">
                                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ ($error['http_status'] ?? 0) >= 500 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ $error['http_status'] ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-gray-500 dark:text-gray-400">
                                            {{ isset($error['created_at']) ? \Carbon\Carbon::parse($error['created_at'])->diffForHumans() : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
