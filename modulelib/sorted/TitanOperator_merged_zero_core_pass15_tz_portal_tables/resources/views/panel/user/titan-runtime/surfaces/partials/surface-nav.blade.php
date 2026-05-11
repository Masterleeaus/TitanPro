<nav class="mb-4 flex flex-wrap gap-2">
    <a href="{{ route('dashboard.user.titan-runtime.boss') }}" class="rounded-full border border-black/10 px-3 py-1 text-xs {{ ($surfaceKey ?? '') === 'boss' ? 'bg-black text-white' : 'bg-white text-black/70' }}">
        Boss
    </a>
    <a href="{{ route('dashboard.user.titan-runtime.go') }}" class="rounded-full border border-black/10 px-3 py-1 text-xs {{ ($surfaceKey ?? '') === 'go' ? 'bg-black text-white' : 'bg-white text-black/70' }}">
        Go
    </a>
    <a href="{{ route('dashboard.user.titan-runtime.dispatch') }}" class="rounded-full border border-black/10 px-3 py-1 text-xs {{ ($surfaceKey ?? '') === 'dispatch' ? 'bg-black text-white' : 'bg-white text-black/70' }}">
        Dispatch
    </a>
    <a href="{{ route('dashboard.user.titan-runtime.qc') }}" class="rounded-full border border-black/10 px-3 py-1 text-xs {{ ($surfaceKey ?? '') === 'qc' ? 'bg-black text-white' : 'bg-white text-black/70' }}">
        QC
    </a>
</nav>
