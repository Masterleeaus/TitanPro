@php
    $user = auth()->user();
@endphp

@if($user && (property_exists($user, 'is_superadmin') ? $user->is_superadmin : ($user->is_admin ?? false)))
    <li class="sidebar-item {{ request()->routeIs('titan-docs.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('titan-docs.document.index') }}">
            <i class="ti ti-file-text"></i>
            <span>{{ __('Titan Docs') }}</span>
        </a>
    </li>
@endif
