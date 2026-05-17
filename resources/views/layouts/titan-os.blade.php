<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Titan OS') }}</title>

    {{-- Compile and load the OS and assistant assets.  These files are
         registered in vite.config.ts so they will be bundled with the rest
         of the application.  --}}
    @if (function_exists('vite'))
        @vite([
            'resources/css/titan-os.css',
            'resources/js/titan-os.js',
            'resources/css/titan-zero-assistant.css',
            'resources/js/titan-zero-assistant.js',
        ])
    @else
        <link rel="stylesheet" href="{{ asset('css/titan-os.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/titan-zero-assistant.css') }}" />
        <script src="{{ asset('js/titan-os.js') }}" defer></script>
        <script src="{{ asset('js/titan-zero-assistant.js') }}" defer></script>
    @endif
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 min-h-screen">
    {{-- Include the Business OS shell.  For OS pages we include the workspace
         frame and disable duplicate asset loading (assets are loaded above). --}}
    @include('titan-os.shell', [
        'includeWorkspace' => true,
        'loadAssets' => false,
    ])

    <main class="titan-os-page container mx-auto p-4">
        @yield('content')
    </main>
</body>
</html>