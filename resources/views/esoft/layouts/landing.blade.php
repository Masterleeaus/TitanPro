<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Titan Zero') }}</title>
    @if(isset($logo5))
        <link rel="shortcut icon" href="/templates/esoft/img/logo/title5.png" type="image/x-icon">
    @elseif(isset($logo3))
        <link rel="shortcut icon" href="/templates/esoft/img/logo/titile3.png" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="/templates/esoft/img/logo/titel1.png" type="image/x-icon">
    @endif
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $hasEsoftCss = is_array($manifest) && array_key_exists('resources/scss/esoft/main.scss', $manifest);
    @endphp
    @if($hasEsoftCss)
        @vite(['resources/scss/esoft/main.scss'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="/templates/esoft/css/esoft-fallback.css">
    @endif
    @yield('css')
</head>
<body class="body2 body @yield('body_attribute')">
    @include('esoft.layouts.partials.loader')
    @include('esoft.layouts.partials.header.navbar6')
    @yield('content')
    @include('esoft.layouts.partials.cta')
    @include('esoft.layouts.partials.footer')
    @yield('scripts')
    @php
        $hasEsoftJs = is_array($manifest) && array_key_exists('resources/js/esoft/main.js', $manifest);
    @endphp
    @if($hasEsoftJs)
        @vite(['resources/js/esoft/main.js'])
    @else
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
        <script src="/templates/esoft/js/esoft-fallback.js"></script>
    @endif
</body>
</html>
