@php($diagnostics = \App\Support\ThemePolicyManager::diagnostics())

<x-filament::section>
    <x-slot name="heading">Theme Policy</x-slot>
    <x-slot name="description">Role, tenant, and user level theme assignments.</x-slot>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border p-4">
            <div class="text-sm opacity-70">Role policies</div>
            <div class="text-2xl font-bold">{{ $diagnostics['counts']['roles'] }}</div>
        </div>
        <div class="rounded-xl border p-4">
            <div class="text-sm opacity-70">Tenant policies</div>
            <div class="text-2xl font-bold">{{ $diagnostics['counts']['tenants'] }}</div>
        </div>
        <div class="rounded-xl border p-4">
            <div class="text-sm opacity-70">User policies</div>
            <div class="text-2xl font-bold">{{ $diagnostics['counts']['users'] }}</div>
        </div>
    </div>

    <pre class="mt-4 max-h-96 overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($diagnostics['policy'], JSON_PRETTY_PRINT) }}</pre>
</x-filament::section>
