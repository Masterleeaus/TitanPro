<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- ===== FAB ICON ===== --}}
    @if(isset($logo5))
        <link rel="shortcut icon" href="/img/logo/title5.png" type="image/x-icon">
    @elseif(isset($logo3))
        <link rel="shortcut icon" href="/img/logo/titile3.png" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="/img/logo/titel1.png" type="image/x-icon">
    @endif

    <!--===== CSS LINK =======-->
    @vite(['resources/esoft/scss/main.scss'])

    @yield('css')

</head>

<body class="body2 body @yield('body_attribute')">

    @include('templates.esoft.layouts.partials.loader')

    @include('templates.esoft.layouts.partials.header.navbar6')


    @yield('content')

    
    @include('templates.esoft.layouts.partials.cta')

    @include('templates.esoft.layouts.partials.footer')

    @yield('scripts')

    @vite(['resources/esoft/js/main.js'])

</body>

</html>