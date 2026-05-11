namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\sections;


@php
    $user = auth()->user();
    $mods = function_exists('user_modules') ? user_modules() : [];
    $can = $user && method_exists($user, 'can') ? $user->can('view_jobmanager') : false;
    $isAdmin = $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false;
@endphp

@if ((is_array($mods) && in_array('jobmanager', $mods)) || $can || $isAdmin)
    <li class="sidebar-item">
        <a class="sidebar-link" href="{ route('jobmanager.index') }">
            <i class="fa fa-briefcase sidebar-icon"></i>
            <span>{ __('modules.module.jobmanager') }</span>
        </a>
    </li>
@endif
