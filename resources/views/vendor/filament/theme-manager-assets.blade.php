{{-- Active Theme Manager runtime assets --}}
@if (! empty($activeThemeAssets ?? []))
    @foreach ($activeThemeAssets as $asset)
        @php($path = parse_url($asset, PHP_URL_PATH) ?? '')
        @if (str_ends_with($path, '.css'))
            <link rel="stylesheet" href="{{ $asset }}">
        @elseif (str_ends_with($path, '.js'))
            <script defer src="{{ $asset }}"></script>
        @endif
    @endforeach
@endif
