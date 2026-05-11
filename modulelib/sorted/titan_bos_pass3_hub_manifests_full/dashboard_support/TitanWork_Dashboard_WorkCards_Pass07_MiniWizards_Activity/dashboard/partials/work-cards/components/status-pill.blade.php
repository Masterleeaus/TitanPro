@props([
    'tone' => 'slate',
    'label' => '',
])

@php
    $classes = match ($tone) {
        'green', 'success' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
        'red', 'danger' => 'bg-rose-500/12 text-rose-600 dark:text-rose-400',
        'amber', 'warning' => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
        'sky', 'info' => 'bg-sky-500/12 text-sky-600 dark:text-sky-400',
        'violet' => 'bg-violet-500/12 text-violet-600 dark:text-violet-400',
        default => 'bg-foreground/[0.06] text-foreground/70',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-[11px] font-semibold', $classes]) }}>
    {{ $label ?: $slot }}
</span>
