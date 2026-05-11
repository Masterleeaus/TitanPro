@if (!$titan_operator['active'])
    <p>
        @lang('This titan_operator is not active.')
    </p>
@else
    @php
        $frontendView = 'titan-operator::frontend-ui.frontend-ui';
        $routes = [
            'index' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.index') ? route('api.v2.titan_operator.index', [$titan_operator->getAttribute('uuid'), $session]) : null,
            'getSession' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.index.session') ? route('api.v2.titan_operator.index.session', [$titan_operator->getAttribute('uuid'), $session]) : null,
            'conversations' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.conversion.store') ? route('api.v2.titan_operator.conversion.store', [$titan_operator->getAttribute('uuid'), $session]) : null,
            'send-email' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.send-email.store') ? route('api.v2.titan_operator.send-email.store', [$titan_operator->getAttribute('uuid'), $session]) : null,
            'collect-email' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.collect.email') ? route('api.v2.titan_operator.collect.email', [$titan_operator->getAttribute('uuid'), $session]) : null,
            'articles' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.articles') ? route('api.v2.titan_operator.articles', [$titan_operator->getAttribute('uuid')]) : null,
            'enable-sound' => \Illuminate\Support\Facades\Route::has('api.v2.titan_operator.enable-sound') ? route('api.v2.titan_operator.enable-sound', [$titan_operator->getAttribute('uuid'), $session]) : null,
        ];
    @endphp

    @if (\Illuminate\Support\Facades\View::exists($frontendView))
        @include($frontendView, [
            'is_editor' => false,
            'is_iframe' => true,
            'session' => $session,
            'titan_operator' => $titan_operator,
            'conversations' => $conversations,
            'routes' => array_filter($routes),
        ])
    @else
        <p>@lang('Titan Operator frontend UI view is not available.')</p>
    @endif
@endif
