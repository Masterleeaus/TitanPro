<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">PWA Preview Bridge</x-slot>
        <x-slot name="description">Preview the technician PWA and launch admin preview links.</x-slot>

        <div class="grid gap-4 lg:grid-cols-[2fr_1fr]">
            <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                <iframe
                    src="{{ url('/technician/dashboard?admin_preview=1') }}"
                    title="TitanGo PWA Preview"
                    class="h-[420px] w-full"
                    sandbox="allow-same-origin allow-scripts allow-forms"
                ></iframe>
            </div>

            <div class="space-y-3">
                <a
                    href="{{ url('/technician/dashboard?admin_preview=1') }}"
                    target="_blank"
                    class="block rounded-xl border border-gray-200 p-3 font-medium transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                >
                    Open Generic PWA Preview
                </a>

                @foreach ($technicians as $technician)
                    <a
                        href="{{ url('/technician/dashboard?admin_preview=1&technician_id='.$technician->id) }}"
                        target="_blank"
                        class="block rounded-xl border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                    >
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $technician->name }}</div>
                        <div class="text-xs text-gray-500">Preview as technician</div>
                    </a>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
