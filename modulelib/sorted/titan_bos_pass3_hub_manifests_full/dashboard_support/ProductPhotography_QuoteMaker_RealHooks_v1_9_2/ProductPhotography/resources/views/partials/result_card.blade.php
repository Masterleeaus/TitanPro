<x-card variant="outline" size="md" class="h-full">
    <div class="mb-3 flex items-start justify-between gap-3">
        <div>
            <h3 class="mb-1 text-sm font-semibold text-heading-foreground">
                {{ $item->result_title ?? __('Quote visual draft') }}
            </h3>
            <p class="mb-0 text-2xs text-foreground/60">
                {{ $item->result_summary ?? __('Prepared for quote support and customer approval.') }}
            </p>
        </div>

        <span class="rounded-full bg-foreground/5 px-2 py-1 text-3xs uppercase tracking-wide text-foreground/60">
            {{ $item->status ?? 'draft' }}
        </span>
    </div>

    <div class="flex flex-wrap gap-2 text-3xs text-foreground/60">
        <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $item->service_type ?? __('Service') }}</span>
        <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $item->visual_mode ?? 'quote_preview' }}</span>
        <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $item->package_tier ?? 'standard' }}</span>
        @if(!empty($item->quote_reference))
            <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $item->quote_reference }}</span>
        @endif
    </div>
</x-card>
