<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Offline sync</x-slot>
        <x-slot name="description">Cleaner device queue status for photos, checklists, location and job updates.</x-slot>
        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-700"><div class="text-xs uppercase tracking-wide text-gray-500">Queued uploads</div><div class="mt-2 text-3xl font-bold">{{ $summary['queued'] ?? 0 }}</div></div>
            <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-700"><div class="text-xs uppercase tracking-wide text-gray-500">Uploaded today</div><div class="mt-2 text-3xl font-bold">{{ $summary['uploaded'] ?? 0 }}</div></div>
            <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-700"><div class="text-xs uppercase tracking-wide text-gray-500">Last sync</div><div class="mt-2 text-sm font-semibold">{{ ($summary['last_sync'] ?? now())->diffForHumans() }}</div></div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
