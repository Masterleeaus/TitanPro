{{--
    SSR UI Override styles — injected into the Filament panel <head> via the
    `panels::head.end` render hook registered in RegistersFilamentPlugins.

    Reads every UiOverride saved for the current user's organisation and emits
    them as an inline <style> block so the overrides are applied before the
    browser paints the first frame.  This eliminates the flash of unstyled
    content that would otherwise occur while Alpine.js boots.

    The Alpine inspector still applies live edits on top of these rules via
    inline styles (higher specificity), so real-time editing continues to work
    as before.  The Alpine DOMContentLoaded bootstrap skips its initial
    localStorage re-apply when it detects this block (id="titan-ui-override-ssr"),
    preventing redundant double-application on first load.
--}}

@php
    /** @var int|null $orgId */
    $orgId = auth()->user()?->organization_id
        ? (int) auth()->user()->organization_id
        : null;

    $css = \App\Services\UiOverrideCssRenderer::render($orgId);
@endphp

@if ($css)
    <style id="titan-ui-override-ssr" data-ssr="1">{!! $css !!}</style>
@endif
