<x-filament::section>
    <x-slot name="heading">UI Studio Preview</x-slot>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border p-4">
            <div class="text-xs font-medium opacity-70">Desktop</div>
            <div class="mt-3 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-900">
                <div class="mb-3 h-6 w-32 rounded bg-primary-500"></div>
                <div class="space-y-2">
                    <div class="h-3 rounded bg-gray-200 dark:bg-gray-700"></div>
                    <div class="h-3 w-2/3 rounded bg-gray-200 dark:bg-gray-700"></div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border p-4">
            <div class="text-xs font-medium opacity-70">Tablet</div>
            <div class="mx-auto mt-3 max-w-48 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-900">
                <div class="mb-3 h-6 w-24 rounded bg-primary-500"></div>
                <div class="space-y-2">
                    <div class="h-3 rounded bg-gray-200 dark:bg-gray-700"></div>
                    <div class="h-3 w-2/3 rounded bg-gray-200 dark:bg-gray-700"></div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border p-4">
            <div class="text-xs font-medium opacity-70">Mobile</div>
            <div class="mx-auto mt-3 max-w-28 rounded-lg border bg-white p-3 shadow-sm dark:bg-gray-900">
                <div class="mb-3 h-5 w-16 rounded bg-primary-500"></div>
                <div class="space-y-2">
                    <div class="h-2 rounded bg-gray-200 dark:bg-gray-700"></div>
                    <div class="h-2 w-2/3 rounded bg-gray-200 dark:bg-gray-700"></div>
                </div>
            </div>
        </div>
    </div>
</x-filament::section>
