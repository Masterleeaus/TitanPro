@php
    $templateName = is_array($template) ? ($template['name'] ?? 'Template') : ($template->name ?? 'Template');
    $templateSummary = is_array($template) ? ($template['summary'] ?? '') : ($template->summary ?? '');
    $templateService = is_array($template) ? ($template['service_type'] ?? 'Service') : ($template->service_type ?? 'Service');
    $templateVariation = is_array($template) ? ($template['variation'] ?? 'Standard') : ($template->variation ?? 'Standard');
    $templateTheme = is_array($template) ? ($template['theme'] ?? 'Clean Professional') : ($template->theme ?? 'Clean Professional');
    $templateMeta = is_array($template) ? ($template['metadata_json'] ?? null) : ($template->metadata_json ?? null);
    $decodedMeta = is_string($templateMeta) ? json_decode($templateMeta, true) : (is_array($templateMeta) ? $templateMeta : []);
    $templateMode = $decodedMeta['quote_bridge']['mode'] ?? (str_contains(strtolower($templateSummary), 'invoice') ? 'invoice' : (str_contains(strtolower($templateSummary), 'booking') ? 'booking' : 'quote'));
@endphp

<x-card variant="outline" size="md" class="h-full">
    <div class="mb-3">
        <div class="mb-2 flex items-center justify-between gap-2">
            <h3 class="mb-0 text-sm font-semibold text-heading-foreground">{{ $templateName }}</h3>
            <span class="rounded-full bg-primary/10 px-2 py-1 text-3xs font-medium uppercase text-primary">{{ $templateMode }}</span>
        </div>
        <p class="mb-0 text-2xs text-foreground/60">{{ $templateSummary }}</p>
    </div>

    <div class="mb-3 flex flex-wrap gap-2 text-3xs text-foreground/60">
        <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $templateService }}</span>
        <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $templateVariation }}</span>
        <span class="rounded-full bg-foreground/5 px-2 py-1">{{ $templateTheme }}</span>
    </div>

    <div class="text-2xs text-foreground/50">
        {{ __('Use this as a starting point for faster and more accurate revenue flow building.') }}
    </div>
</x-card>
