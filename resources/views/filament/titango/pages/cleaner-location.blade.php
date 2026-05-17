<x-filament-panels::page>
    <div class="space-y-6" x-data="titanGoCleanerLocation('{{ $endpoint }}')" x-init="restore()">
        <x-filament::section>
            <x-slot name="heading">Cleaner location sharing</x-slot>
            <x-slot name="description">Turn this on while you are on shift so the office can see that you are travelling, on site, or available for the next assigned clean.</x-slot>

            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <button type="button" @click="toggle()" class="w-full rounded-2xl px-5 py-4 text-base font-bold shadow-sm transition sm:w-auto" :class="enabled ? 'bg-green-600 text-white' : 'bg-gray-950 text-white dark:bg-white dark:text-gray-950'">
                    <span x-text="enabled ? 'Cleaner location is on' : 'Turn on cleaner location'"></span>
                </button>

                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Status</div>
                        <div class="mt-1 font-semibold" x-text="enabled ? 'Sharing every 30 seconds' : 'Off'"></div>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Last sync</div>
                        <div class="mt-1 font-semibold" x-text="lastSent || 'Not synced yet'"></div>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Used for</div>
                        <div class="mt-1 font-semibold">Cleaner safety + proof of arrival</div>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900 dark:bg-amber-900/20 dark:text-amber-200" x-show="permissionDenied">
                    Location permission is blocked. Open your browser site settings and allow location for this site.
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
<script>
    function titanGoCleanerLocation(endpoint) {
        return {
            endpoint,
            enabled: false,
            permissionDenied: false,
            lastSent: '',
            timer: null,
            restore() {
                this.enabled = localStorage.getItem('titango.cleanerLocation.enabled') === 'true';
                if (this.enabled) this.start();
            },
            toggle() { this.enabled ? this.stop() : this.start(); },
            start() {
                if (!navigator.geolocation) { this.permissionDenied = true; return; }
                this.enabled = true;
                localStorage.setItem('titango.cleanerLocation.enabled', 'true');
                this.send();
                if (!this.timer) this.timer = setInterval(() => this.send(), 30000);
            },
            stop() {
                this.enabled = false;
                localStorage.setItem('titango.cleanerLocation.enabled', 'false');
                if (this.timer) clearInterval(this.timer);
                this.timer = null;
            },
            send() {
                navigator.geolocation.getCurrentPosition(
                    position => this.post(position),
                    error => { if (error.code === 1) { this.permissionDenied = true; this.stop(); } },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 15000 }
                );
            },
            post(position) {
                fetch(this.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        heading: position.coords.heading,
                        speed: position.coords.speed,
                        recorded_at: new Date(position.timestamp).toISOString(),
                    }),
                }).then(() => this.lastSent = new Date().toLocaleTimeString()).catch(() => {});
            }
        };
    }
</script>
