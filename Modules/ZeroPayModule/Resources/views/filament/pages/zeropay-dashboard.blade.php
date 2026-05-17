<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sessions</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalSessions }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Sessions</div>
            <div class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $this->completedSessions }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</div>
            <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($this->totalRevenue, 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Deposits</div>
            <div class="mt-2 text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $this->pendingDeposits }}</div>
        </div>
    </div>
</x-filament-panels::page>
