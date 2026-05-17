<x-filament-panels::page>
    <div class="space-y-6" x-data="titanGoProofUpload(@js($proofEndpoint))">
        <x-filament::section>
            <x-slot name="heading">Proof-of-work studio</x-slot>
            <x-slot name="description">Upload before, after, issue, and signature photos from the cleaner app.</x-slot>
            <form class="grid gap-4" @submit.prevent="upload($event)">
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="grid gap-1 text-sm font-medium">Job
                        <select name="job_id" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="">Select job</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}">{{ $job->reference }} — {{ $job->description ?: 'Cleaning job' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid gap-1 text-sm font-medium">Photo type
                        <select name="type" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="before">Before photo</option>
                            <option value="after">After photo</option>
                            <option value="issue">Issue photo</option>
                            <option value="signature">Signature / approval</option>
                            <option value="other">Other proof</option>
                        </select>
                    </label>
                </div>
                <label class="grid gap-1 text-sm font-medium">Photo
                    <input name="photo" type="file" accept="image/*" capture="environment" class="rounded-xl border border-gray-300 p-3 dark:border-gray-700">
                </label>
                <label class="grid gap-1 text-sm font-medium">Note
                    <textarea name="note" rows="3" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Optional issue, room, or customer note"></textarea>
                </label>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white">Upload proof</button>
                    <a href="{{ url('/titango/jobs') }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold dark:border-gray-700">Back to jobs</a>
                </div>
                <div class="text-sm text-gray-500" x-text="status"></div>
            </form>
        </x-filament::section>
    </div>
    @once
        <script>
            function titanGoProofUpload(endpoint) {
                return {
                    endpoint,
                    status: '',
                    upload(event) {
                        const form = event.target;
                        const data = new FormData(form);
                        this.status = 'Uploading proof...';
                        navigator.geolocation?.getCurrentPosition(position => {
                            data.append('latitude', position.coords.latitude);
                            data.append('longitude', position.coords.longitude);
                            this.send(data, form);
                        }, () => this.send(data, form), { enableHighAccuracy: true, timeout: 5000 });
                    },
                    send(data, form) {
                        fetch(this.endpoint, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                            credentials: 'same-origin',
                            body: data,
                        }).then(r => { if (!r.ok) throw new Error('Upload failed'); return r.json(); })
                          .then(() => { this.status = 'Proof uploaded.'; form.reset(); })
                          .catch(() => { this.status = 'Upload failed. It can be retried when signal improves.'; });
                    }
                }
            }
        </script>
    @endonce
</x-filament-panels::page>
