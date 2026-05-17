{{--
  Titan OS Shell

  This view composes the high‑level Business OS shell that wraps every
  Filament panel.  It pulls together the header, app switcher overlay,
  assistant dock and workspace frame.  Styles and scripts are loaded via
  Vite/Mix to ensure they are compiled with the rest of the project.
--}}
<div id="titan-os-shell" class="titan-os-shell">
    @include('titan-os.partials.header')
    @include('titan-os.partials.app-switcher')
    @include('titan-os.partials.assistant-dock')
    {{-- Only render the workspace frame when explicitly allowed.  In panel
         injections this value will be false to avoid interfering with
         Filament layouts. --}}
    @if (!isset($includeWorkspace) || $includeWorkspace)
        @include('titan-os.partials.workspace-frame')
    @endif
</div>

{{-- Inject OS context as a global JS variable --}}
@include('titan-os.context')

{{-- Conditionally load CSS/JS assets when requested.  Panel injection sets
     loadAssets=true to ensure the assets are present, while the OS layout
     disables additional asset loading to prevent duplicates. --}}
@php
    $loadAssets = $loadAssets ?? false;
@endphp
@if ($loadAssets)
    @if (function_exists('vite'))
        @vite([
            'resources/css/titan-os.css',
            'resources/js/titan-os.js',
            'resources/css/titan-zero-assistant.css',
            'resources/css/chatbot-bubble.css',
            'resources/js/titan-zero-assistant.js',
        ])
    @else
        <link rel="stylesheet" href="{{ asset('css/titan-os.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/titan-zero-assistant.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/chatbot-bubble.css') }}" />
        <script src="{{ asset('js/titan-os.js') }}" defer></script>
        <script src="{{ asset('js/titan-zero-assistant.js') }}" defer></script>
    @endif
@endif
