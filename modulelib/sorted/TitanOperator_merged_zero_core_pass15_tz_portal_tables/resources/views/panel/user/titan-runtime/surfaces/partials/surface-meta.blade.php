<div class="mb-4 grid gap-3 md:grid-cols-3">
    <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
        <div class="text-[11px] uppercase text-black/45">Runtime Hub</div>
        <div class="text-sm font-semibold" data-runtime-meta="hub">{{ $surfaceKey ?? 'boss' }}</div>
    </div>
    <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
        <div class="text-[11px] uppercase text-black/45">Node</div>
        <div class="text-sm font-semibold" data-runtime-meta="node">local-web-node</div>
    </div>
    <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
        <div class="text-[11px] uppercase text-black/45">Surface</div>
        <div class="text-sm font-semibold">{{ ucfirst($surfaceKey ?? 'boss') }}</div>
    </div>
</div>
