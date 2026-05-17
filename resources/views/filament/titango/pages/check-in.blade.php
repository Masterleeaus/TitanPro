@php
    $locationEndpoint = $locationEndpoint ?? route('technician.location.store', absolute: false);
    $jobs = $jobs ?? collect();
@endphp

<x-filament-panels::page>
    <div class="space-y-6" x-data="titanGoCleanerLocation(@js($locationEndpoint))" x-init="restore()">
        <x-filament::section>
            <x-slot name="heading">Cleaner location and site check-in</x-slot>
            <x-slot name="description">Turn on cleaner location before starting. Check in when you arrive and check out when leaving the site.</x-slot>
            <div class="flex flex-wrap gap-3">
                <button type="button" @click="toggle()" class="rounded-2xl px-4 py-3 text-sm font-semibold shadow-sm" :class="enabled ? 'bg-green-600 text-white' : 'bg-gray-950 text-white dark:bg-white dark:text-gray-950'">
                    <span x-text="enabled ? 'Cleaner location on' : 'Turn on cleaner location'"></span>
                </button>
                <a href="{{ url('/titango/sync') }}" class="rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold dark:border-gray-700">Sync status</a>
            </div>
            <div class="mt-3 text-xs text-amber-700" x-show="permissionDenied">Location permission blocked.</div>
            <div class="mt-3 text-xs text-gray-500" x-show="lastSent">Last location sync: <span x-text="lastSent"></span></div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Jobs ready for check-in</x-slot>
            <div class="space-y-3">
                @forelse ($jobs as $job)
                    <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $job->reference }}</div>
                        <div class="text-sm text-gray-500">{{ $job->description ?: 'Cleaning job' }}</div>
                        <div class="mt-1 text-xs text-gray-400">{{ $job->scheduled_start?->format('D d M, g:ia') ?? 'Not scheduled' }}</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button type="button" class="rounded-xl bg-green-600 px-3 py-2 text-xs font-semibold text-white">Arrived</button>
                            <button type="button" class="rounded-xl bg-gray-950 px-3 py-2 text-xs font-semibold text-white dark:bg-white dark:text-gray-950">Start work</button>
                            <button type="button" class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold dark:border-gray-700">Leaving site</button>
                            <a href="{{ url('/titango/field-photo-log-page') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold dark:border-gray-700">Upload photos</a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700">No active jobs.</div>
                @endforelse
            </div>
        </x-filament::section>
    </div>
    <script>
        function titanGoCleanerLocation(endpoint){return{endpoint:endpoint||'/api/technician/location',enabled:false,permissionDenied:false,lastSent:'',timer:null,restore(){this.enabled=localStorage.getItem('titango.cleanerLocation.enabled')==='true';if(this.enabled)this.start()},toggle(){this.enabled?this.stop():this.start()},start(){if(!navigator.geolocation){this.permissionDenied=true;return}this.enabled=true;localStorage.setItem('titango.cleanerLocation.enabled','true');this.send();if(!this.timer)this.timer=setInterval(()=>this.send(),30000)},stop(){this.enabled=false;localStorage.setItem('titango.cleanerLocation.enabled','false');if(this.timer)clearInterval(this.timer);this.timer=null},send(){navigator.geolocation.getCurrentPosition(p=>this.post(p),e=>{if(e.code===1){this.permissionDenied=true;this.stop()}},{enableHighAccuracy:true,timeout:10000,maximumAge:15000})},post(p){fetch(this.endpoint,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},credentials:'same-origin',body:JSON.stringify({latitude:p.coords.latitude,longitude:p.coords.longitude,heading:p.coords.heading,speed:p.coords.speed,recorded_at:new Date(p.timestamp).toISOString()})}).then(()=>this.lastSent=new Date().toLocaleTimeString()).catch(()=>{})}}}
    </script>
</x-filament-panels::page>
