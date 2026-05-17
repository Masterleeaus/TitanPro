@php($jobs = $jobs ?? collect())
<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Proof-of-work photos</x-slot>
            <x-slot name="description">Capture before, after, issue, and completion photos for assigned cleaning jobs. Photos use the original PWA upload endpoint and queue cleanly when service-worker sync is available.</x-slot>
            <div class="space-y-3">
                @forelse ($jobs as $job)
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900" x-data="titanGoPhotoUploader({{ (int) $job->id }})">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $job->reference }}</div>
                                <div class="text-sm text-gray-500">{{ $job->description ?: 'Cleaning job' }}</div>
                                <div class="mt-1 text-xs" x-text="status"></div>
                            </div>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach (['before' => 'Before photo', 'after' => 'After photo', 'issue' => 'Issue photo', 'completion' => 'Completion photo'] as $tag => $label)
                                    <label class="cursor-pointer rounded-xl {{ in_array($tag, ['before','after'], true) ? 'bg-gray-950 text-white dark:bg-white dark:text-gray-950' : 'border border-gray-200 dark:border-gray-700' }} px-3 py-2 text-center text-xs font-semibold">
                                        {{ $label }}
                                        <input type="file" class="hidden" accept="image/*" capture="environment" @change="upload($event, '{{ $tag }}')">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700">No jobs available for photo upload.</div>
                @endforelse
            </div>
        </x-filament::section>
    </div>
    <script>
        function titanGoPhotoUploader(jobId){return{status:'',upload(event,tag){const file=event.target.files?.[0];if(!file)return;const data=new FormData();data.append('photo',file);data.append('tag',tag);this.status='Uploading '+tag+' photo…';fetch(`/api/technician/jobs/${jobId}/photos`,{method:'POST',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},credentials:'same-origin',body:data}).then(response=>{if(!response.ok)throw new Error('Upload failed');this.status='Uploaded '+tag+' photo';event.target.value='';}).catch(()=>{this.status='Upload queued/failed. Try again when online.';});}}}
    </script>
</x-filament-panels::page>
