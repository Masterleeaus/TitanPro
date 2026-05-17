<x-filament-panels::page>
    <div class="space-y-6" x-data="titanGoMissionPack(@js($missionPackEndpoint), @js($syncEndpoint))">
        <x-filament::section>
            <x-slot name="heading">Offline mission pack</x-slot>
            <x-slot name="description">Download today’s jobs, notes, and proof requirements before heading on site.</x-slot>
            <div class="grid gap-4 md:grid-cols-3">
                <button type="button" @click="download()" class="rounded-2xl bg-orange-600 p-5 text-left font-semibold text-white">Download today’s pack</button>
                <button type="button" @click="sync()" class="rounded-2xl border border-gray-200 p-5 text-left font-semibold dark:border-gray-700">Sync queued actions</button>
                <div class="rounded-2xl border border-gray-200 p-5 dark:border-gray-700"><div class="text-sm text-gray-500">Queued actions</div><div class="mt-1 text-3xl font-bold" x-text="queueCount"></div></div>
            </div>
            <div class="mt-4 rounded-xl bg-gray-50 p-4 text-sm text-gray-600 dark:bg-gray-900 dark:text-gray-300" x-text="status || 'No pack downloaded yet.'"></div>
        </x-filament::section>
    </div>
    @once
        <script>
            function titanGoMissionPack(packEndpoint, syncEndpoint) {
                return {
                    status: '',
                    queueCount: JSON.parse(localStorage.getItem('titango.offlineQueue') || '[]').length,
                    download() { fetch(packEndpoint, { credentials: 'same-origin', headers: { 'Accept': 'application/json' }}).then(r => r.json()).then(data => { localStorage.setItem('titango.missionPack', JSON.stringify(data)); this.status = `Pack ready: ${data.jobs?.length || 0} jobs, expires ${data.expires_at || 'later'}`; }).catch(() => this.status = 'Could not download pack.'); },
                    sync() { const actions = JSON.parse(localStorage.getItem('titango.offlineQueue') || '[]'); fetch(syncEndpoint, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }, credentials: 'same-origin', body: JSON.stringify({ actions }) }).then(r => r.json()).then(data => { localStorage.removeItem('titango.offlineQueue'); this.queueCount = 0; this.status = `Synced ${data.accepted || 0} actions.`; }).catch(() => this.status = 'Sync failed. Actions remain queued.'); }
                }
            }
        </script>
    @endonce
</x-filament-panels::page>
