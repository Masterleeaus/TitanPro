<x-filament-panels::page>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Draft</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $draftCount }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Sent</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $sentCount }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Accepted</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $acceptedCount }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Converted</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $convertedCount }}</p>
        </div>
    </div>
</x-filament-panels::page>
