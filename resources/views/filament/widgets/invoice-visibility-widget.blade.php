@php
    use App\Support\CleaningAdminMetrics;
    $totals = CleaningAdminMetrics::dashboardTotals();
    $recentPayments = CleaningAdminMetrics::recentPayments();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Invoice Visibility</x-slot>
        <x-slot name="description">Overdue and draft counts with the latest recorded payments.</x-slot>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Overdue Invoices</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['overdue_invoices'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Draft Invoices</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['draft_invoices'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Recent Payments</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $recentPayments->count() }}</div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Paid</th>
                        <th class="px-3 py-2 text-left font-medium">Customer</th>
                        <th class="px-3 py-2 text-left font-medium">Invoice</th>
                        <th class="px-3 py-2 text-right font-medium">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($recentPayments as $payment)
                        <tr>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300">{{ optional($payment->paid_at)->format('d M Y') }}</td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-200">
                                {{ $payment->invoice?->customer?->business_name ?: trim(($payment->invoice?->customer?->first_name ?? '') . ' ' . ($payment->invoice?->customer?->last_name ?? '')) ?: 'Unknown customer' }}
                            </td>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300">{{ $payment->invoice?->invoice_number ?? '—' }}</td>
                            <td class="px-3 py-2 text-right">{{ CleaningAdminMetrics::currency($payment->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No payments recorded yet for this organization.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
