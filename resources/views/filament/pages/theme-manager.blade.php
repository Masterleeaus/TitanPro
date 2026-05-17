<x-filament-panels::page>
    <style>
        .theme-manager-page{display:grid;gap:1.25rem}.theme-manager-hero{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem}.theme-manager-eyebrow{margin:0 0 .25rem;color:rgb(165 180 252);font-size:.75rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.theme-manager-hero h2{margin:0;font-size:1.75rem;font-weight:850}.theme-manager-hero p{margin:.35rem 0 0;max-width:52rem;color:rgb(161 161 170)}.theme-manager-active{min-width:13rem;border:1px solid rgba(255,255,255,.12);border-radius:1rem;padding:.85rem;background:rgba(255,255,255,.035)}.theme-manager-active span,.theme-manager-active strong{display:block}.theme-manager-active span{color:rgb(161 161 170);font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em}.theme-manager-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}.theme-manager-card{position:relative;min-height:15rem;text-align:left;cursor:pointer;border:1px solid var(--tm-border);border-radius:1.25rem;padding:1rem;background:radial-gradient(circle at top right,color-mix(in srgb,var(--tm-accent) 16%,transparent),transparent 38%),linear-gradient(180deg,color-mix(in srgb,var(--tm-surface) 94%,white 6%),var(--tm-bg));color:var(--tm-text);box-shadow:0 18px 60px rgba(0,0,0,.2);overflow:hidden}.theme-manager-card:hover,.theme-manager-card.is-active{outline:2px solid var(--tm-primary);outline-offset:2px}.theme-manager-card.is-active::after{content:"✓";position:absolute;top:.75rem;right:.75rem;width:1.6rem;height:1.6rem;display:grid;place-items:center;border-radius:999px;background:linear-gradient(135deg,var(--tm-primary),var(--tm-secondary));color:white;font-weight:900}.theme-manager-card-title strong,.theme-manager-card-title em{display:block}.theme-manager-card-title strong{color:var(--tm-primary);font-size:1.05rem}.theme-manager-card-title em{margin-top:.25rem;color:var(--tm-muted);font-size:.78rem;font-style:normal}.theme-manager-preview{display:grid;grid-template-columns:.32fr 1fr;gap:.6rem;height:6.25rem;margin-top:1rem;border:1px solid rgba(255,255,255,.1);border-radius:.75rem;padding:.6rem;background:linear-gradient(135deg,var(--tm-dark),var(--tm-surface-2));overflow:hidden}.theme-manager-preview i{display:block;border-radius:.5rem;background:linear-gradient(180deg,var(--tm-primary),var(--tm-secondary),var(--tm-dark-2))}.theme-manager-preview b{display:block;border-radius:.5rem;background:linear-gradient(180deg,var(--tm-surface-3) 0 .65rem,transparent .65rem 1.05rem,var(--tm-primary) 1.05rem 1.42rem,transparent 1.42rem 1.8rem,var(--tm-secondary) 1.8rem 2.17rem,transparent 2.17rem 2.55rem,var(--tm-gray) 2.55rem 3.05rem,transparent 3.05rem 3.45rem,var(--tm-surface-3) 3.45rem 4.1rem)}.theme-manager-swatches{display:flex;gap:.45rem;margin-top:.9rem}.theme-manager-swatches i{width:1.05rem;height:1.05rem;border-radius:999px;background:var(--swatch);box-shadow:inset 0 0 0 1px rgba(255,255,255,.25)}@media(max-width:1100px){.theme-manager-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:640px){.theme-manager-hero{display:block}.theme-manager-active{margin-top:1rem}.theme-manager-grid{grid-template-columns:1fr}}
    </style>
    <div class="theme-manager-page">
        <div class="theme-manager-hero">
            <div>
                <p class="theme-manager-eyebrow">Theme Manager</p>
                <h2>Preset Library</h2>
                <p>Multi-tone schemes with primary pairs, secondary pairs, accents, dark layers and matching greys.</p>
            </div>
            @if ($activePreset && filled($activeTheme))
                <div class="theme-manager-active"><span>Active preset</span><strong>{{ $activeTheme['label'] ?? $activePreset }}</strong></div>
            @endif
        </div>
        <div class="theme-manager-grid">
            @foreach ($this->presets as $slug => $preset)
                <button type="button" wire:click="applyPreset('{{ $slug }}')" class="theme-manager-card {{ $activePreset === $slug ? 'is-active' : '' }}" style="--tm-primary:{{ $preset['primary'] ?? '#38bdf8' }};--tm-secondary:{{ $preset['secondary'] ?? '#2563eb' }};--tm-accent:{{ $preset['accent'] ?? '#22d3ee' }};--tm-dark:{{ $preset['dark'] ?? '#020617' }};--tm-dark-2:{{ $preset['dark_2'] ?? '#07111f' }};--tm-gray:{{ $preset['gray'] ?? '#64748b' }};--tm-bg:{{ $preset['bg'] ?? '#06111d' }};--tm-surface:{{ $preset['surface'] ?? '#101b29' }};--tm-surface-2:{{ $preset['surface_2'] ?? '#14263a' }};--tm-surface-3:{{ $preset['surface_3'] ?? '#1c334d' }};--tm-border:{{ $preset['border'] ?? '#24425f' }};--tm-text:{{ $preset['text'] ?? '#eff6ff' }};--tm-muted:{{ $preset['muted'] ?? '#a9c5df' }};">
                    <span class="theme-manager-card-title"><strong>{{ $preset['label'] ?? str($slug)->headline() }}</strong><em>{{ $preset['description'] ?? 'Theme preset' }}</em></span>
                    <span class="theme-manager-preview"><i></i><b></b></span>
                    <span class="theme-manager-swatches">
                        <i style="--swatch:{{ $preset['primary'] ?? '#38bdf8' }}"></i><i style="--swatch:{{ $preset['secondary'] ?? '#2563eb' }}"></i><i style="--swatch:{{ $preset['accent'] ?? '#22d3ee' }}"></i><i style="--swatch:{{ $preset['dark'] ?? '#020617' }}"></i><i style="--swatch:{{ $preset['gray'] ?? '#64748b' }}"></i>
                    </span>
                </button>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
