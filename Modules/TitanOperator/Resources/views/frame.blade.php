@if (!$titan_operator['active'])
    <p>
        @lang('This titan_operator is not active.')
    </p>
@else
    @php
        $frontendView = 'titan-operator::frontend-ui.frontend-ui';
        $routeIfExists = static fn (string $name, array $parameters = []): ?string => \Illuminate\Support\Facades\Route::has($name)
            ? route($name, $parameters)
            : null;
        $routes = [
            'index' => $routeIfExists('api.v2.titan_operator.index', [$titan_operator->getAttribute('uuid'), $session]),
            'getSession' => $routeIfExists('api.v2.titan_operator.index.session', [$titan_operator->getAttribute('uuid'), $session]),
            'conversations' => $routeIfExists('api.v2.titan_operator.conversion.store', [$titan_operator->getAttribute('uuid'), $session]),
            'send-email' => $routeIfExists('api.v2.titan_operator.send-email.store', [$titan_operator->getAttribute('uuid'), $session]),
            'collect-email' => $routeIfExists('api.v2.titan_operator.collect.email', [$titan_operator->getAttribute('uuid'), $session]),
            'articles' => $routeIfExists('api.v2.titan_operator.articles', [$titan_operator->getAttribute('uuid')]),
            'enable-sound' => $routeIfExists('api.v2.titan_operator.enable-sound', [$titan_operator->getAttribute('uuid'), $session]),
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
