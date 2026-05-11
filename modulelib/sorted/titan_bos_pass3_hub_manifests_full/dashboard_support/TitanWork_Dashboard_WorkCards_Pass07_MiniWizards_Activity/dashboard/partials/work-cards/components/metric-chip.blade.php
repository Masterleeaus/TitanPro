@props([
    'label' => '',
    'value' => '',
])

<div {{ $attributes->class('rounded-xl border border-border bg-background/70 px-3 py-2') }}>
    <div class="text-[10px] font-semibold uppercase tracking-[0.14em] text-foreground/45">{{ $label }}</div>
    <div class="mt-1 text-sm font-semibold text-heading-foreground">{{ $value }}</div>
</div>
