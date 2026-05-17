<x-filament::section>
    <x-slot name="heading">Theme Runtime Diagnostics</x-slot>

    @php($diagnostics = $themeDiagnostics ?? \App\Support\ThemeRuntime::diagnostics())

    <dl class="grid gap-3 text-sm">
        <div>
            <dt class="font-medium">Active theme</dt>
            <dd>{{ $diagnostics['active'] ?? 'none' }}</dd>
        </div>

        <div>
            <dt class="font-medium">Installed themes</dt>
            <dd>{{ implode(', ', $diagnostics['installed'] ?? []) ?: 'none' }}</dd>
        </div>

        <div>
            <dt class="font-medium">Theme assets</dt>
            <dd>
                @forelse (($diagnostics['assets'] ?? []) as $asset)
                    <div class="font-mono text-xs">{{ $asset }}</div>
                @empty
                    <span>none</span>
                @endforelse
            </dd>
        </div>

        <div>
            <dt class="font-medium">Issues</dt>
            <dd>
                @forelse (($diagnostics['issues'] ?? []) as $issue)
                    <div class="text-danger-600">{{ $issue }}</div>
                @empty
                    <span>none</span>
                @endforelse
            </dd>
        </div>
    </dl>
</x-filament::section>
