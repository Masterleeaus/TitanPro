<x-filament-panels::page>
    @php($health = $this->getHealthData())

    <div class="grid gap-6 lg:grid-cols-4">
        <x-filament::section>
            <x-slot name="heading">Queue depth</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">{{ number_format($health['queue_depth']) }}</div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Failed jobs</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">{{ number_format($health['failed_jobs']) }}</div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Failed in last hour</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">{{ number_format($health['failed_jobs_last_hour']) }}</div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Error rate</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">{{ number_format($health['error_rate_percent'], 2) }}%</div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
