{{-- Safe no-op replacement for missing PAGE UI inspector opt-in view. --}}
@php
    $pageUiEnabled = request()->boolean('page_ui_edit') || request()->boolean('ui_inspector');
@endphp

@if ($pageUiEnabled)
    @includeIf('filament.ui-inspector')
@endif
