@php
    $user = auth()->user();
    $modules = function_exists('user_modules') ? user_modules() : [];
    $hasModule = $user && (empty($modules) || in_array('titan-docs', $modules));
@endphp

@if($hasModule)
    <li class="sidebar-item {{ request()->routeIs('titan-docs.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('titan-docs.document.index') }}">
            <i class="ti ti-file-text"></i>
            <span>{{ __('Titan Docs') }}</span>
        </a>
    </li>
@endif
