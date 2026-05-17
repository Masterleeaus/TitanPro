@php
    $brand = $platformBranding ?? [];
    $appTitle = $brand['meta_title'] ?? $brand['app_name'] ?? config('app.name', 'TITAN ZERO');
    $faviconUrl = $brand['favicon_url'] ?? null;
    $themeColor = $brand['theme_color'] ?? '#0f172a';
    $headingFont = $brand['font_heading'] ?? 'Figtree';
    $fontSourceUrl = $brand['font_source_url'] ?? null;
    $fontPath = $brand['font_path'] ?? null;
    $bgImagePath = $brand['bg_image_path'] ?? null;
    $bgImageUrl = $bgImagePath ? \Illuminate\Support\Facades\Storage::disk('public')->url($bgImagePath) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title inertia>{{ $appTitle }}</title>
        @if (! empty($brand['meta_description']))
            <meta name="description" content="{{ $brand['meta_description'] }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        @if ($faviconUrl)
            <link rel="icon" href="{{ $faviconUrl }}">
        @else
            <link rel="icon" type="image/svg+xml" href="/favicon.svg">
            <link rel="icon" type="image/x-icon" href="/favicon.ico">
        @endif

        <!-- PWA -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="{{ $themeColor }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ $brand['app_name'] ?? 'TITAN ZERO' }}">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @if ($fontSourceUrl)
            <link rel="stylesheet" href="{{ $fontSourceUrl }}">
        @endif

        <style>
{!! $brand['theme_tokens_css'] ?? ':root {}' !!}
            @if ($fontPath)
                @font-face {
                    font-family: "{{ e($headingFont) }}";
                    src: url("{{ e(\Illuminate\Support\Facades\Storage::disk('public')->url($fontPath)) }}");
                    font-display: swap;
                }
            @endif
            body {
                font-family: var(--app-body-font), sans-serif;
                background-color: var(--app-bg);
                @if ($bgImageUrl)
                    background-image: var(--bg-image);
                    background-size: cover;
                    background-repeat: no-repeat;
                @endif
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--app-heading-font), sans-serif;
            }
        </style>
        @if (! empty($brand['custom_css']))
            <style>{!! $brand['custom_css'] !!}</style>
        @endif

        <!-- Scripts -->
        @php
            /*
             * White-screen guard:
             * Inertia page components are loaded by resources/js/app.ts via import.meta.glob().
             * Passing the current page component directly to @vite can fail in production
             * because dynamic page chunks are not always top-level manifest entries.
             */
            $viteHot = file_exists(public_path('hot'));
            $viteManifest = file_exists(public_path('build/manifest.json'));
        @endphp
        @if ($viteHot || $viteManifest)
            @vite(['resources/js/app.ts'])
        @else
            <style>
                .titan-vite-missing {
                    margin: 24px auto;
                    max-width: 960px;
                    border: 1px solid #f59e0b;
                    border-radius: 14px;
                    background: #111827;
                    color: #f9fafb;
                    padding: 20px 24px;
                    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                }
                .titan-vite-missing code { color: #fde68a; }
            </style>
        @endif
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @unless ($viteHot || $viteManifest)
            <div class="titan-vite-missing">
                <h1 style="font-size:22px;font-weight:800;margin:0 0 8px;">Titan assets are not built yet</h1>
                <p style="margin:0 0 12px;line-height:1.6;">The Laravel/Inertia app booted, but <code>public/build/manifest.json</code> is missing. Run the asset build on the server, then clear caches.</p>
                <pre style="white-space:pre-wrap;background:#030712;padding:12px;border-radius:10px;overflow:auto;">npm install
npm run build
php artisan optimize:clear</pre>
            </div>
        @endunless
        @inertia
    </body>
</html>
