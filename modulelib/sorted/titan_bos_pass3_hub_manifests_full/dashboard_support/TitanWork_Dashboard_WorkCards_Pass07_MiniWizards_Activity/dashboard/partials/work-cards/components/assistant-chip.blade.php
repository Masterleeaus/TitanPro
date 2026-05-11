@props([
    'icon' => 'sparkles',
    'tone' => 'default',
])

@php
    $toneClass = match ($tone) {
        'accent' => 'bg-accent/[0.10] text-accent',
        'warning' => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
        'danger' => 'bg-rose-500/12 text-rose-600 dark:text-rose-400',
        default => 'bg-foreground/[0.05] text-foreground/70',
    };
    $iconComponent = 'tabler-' . $icon;
@endphp

<div {{ $attributes->class(['inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[11px] font-semibold', $toneClass]) }}>
    @if (view()->exists('components.' . $iconComponent))
        <x-dynamic-component :component="$iconComponent" class="size-3.5" />
    @else
        <x-tabler-sparkles class="size-3.5" />
    @endif
    <span>{{ $slot }}</span>
</div>
