@if (!$titan_operator['active'])
    <p>
        @lang('This titan_operator is not active.')
    </p>
@else
    @include('titan_operator::frontend-ui.frontend-ui', [
        'is_editor' => false,
        'is_iframe' => true,
        'session' => $session,
        'titan_operator' => $titan_operator,
        'conversations' => $conversations,
        'routes' => [
            'index' => route('api.v2.titan_operator.index', [$titan_operator->getAttribute('uuid')]),
            'getSession' => route('api.v2.titan_operator.index.session', [$titan_operator->getAttribute('uuid'), $session]),
            'conversations' => route('api.v2.titan_operator.conversion.store', [$titan_operator->getAttribute('uuid'), $session]),
            'send-email' => route('api.v2.titan_operator.send-email.store', [$titan_operator->getAttribute('uuid'), $session]),
            'collect-email' => route('api.v2.titan_operator.collect.email', [$titan_operator->getAttribute('uuid'), $session]),
            'articles' => route('api.v2.titan_operator.articles', [$titan_operator->getAttribute('uuid')]),
            'enable-sound' => route('api.v2.titan_operator.enable-sound', [$titan_operator->getAttribute('uuid'), $session]), // Enabled and disabled route
        ],
    ])
@endif