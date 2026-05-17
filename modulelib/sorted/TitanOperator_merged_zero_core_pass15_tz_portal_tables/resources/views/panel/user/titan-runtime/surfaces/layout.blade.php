@extends('panel.layout.app', ['disable_tblr' => true])

@section('title', $title ?? 'Titan Runtime Surface')

@section('content')
    <div
        class="container py-4 titan-runtime-surface"
        x-data="{ runtimeSurface: '{{ $surfaceKey ?? 'boss' }}' }"
        data-runtime-surface="{{ $surfaceKey ?? 'boss' }}"
    >
        @include('panel.user.titan-runtime.surfaces.partials.surface-nav', ['surfaceKey' => $surfaceKey ?? 'boss'])
        @include('panel.user.titan-runtime.surfaces.partials.surface-meta', ['surfaceKey' => $surfaceKey ?? 'boss'])
        @includeIf($wrappedView)
    </div>
@endsection

@push('script')
<script src="{{ asset('vendor/titan-runtime/runtime-surface.js') }}"></script>
@endpush

@push('style')
<link rel="stylesheet" href="{{ asset('vendor/titan-runtime/runtime-surface.css') }}">
@endpush
