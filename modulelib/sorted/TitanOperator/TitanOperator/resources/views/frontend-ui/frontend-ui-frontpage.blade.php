@vite('app/Extensions/TitanOperator/resources/assets/scss/external-titan_operator.scss')
@vite('app/Extensions/TitanOperator/resources/assets/scss/external-titan_operator-tw.scss')

@php
    $style = '';

    if (!empty($titan_operator['color'])) {
        $style .= '--lqd-ext-chat-primary: ' . $titan_operator['color'] . ';';
    }
@endphp

<div
    class="lqd-ext-titan_operator"
    data-pos-x="{{ $titan_operator['position'] ?? 'right' }}"
    data-pos-y="{{ $titan_operator['position_y'] ?? 'bottom' }}"
    data-window-state="{{ $is_iframe ? 'open' : 'close' }}"
    data-embedded="true"
    data-fetching="true"
    x-data="externalTitanOperator"
    :data-fetching="fetching ? 'true' : 'false'"
    @if ($style) style="{{ $style }}" @endif
>
    <div
        class="lqd-ext-titan_operator-window before:pointer-events-none before:absolute before:bottom-0 before:z-3 before:h-40 before:w-full before:bg-gradient-to-t before:from-[--lqd-ext-chat-window-bg] before:from-40% before:to-transparent before:to-85%">
        <div class="lqd-ext-titan_operator-window-contents-wrap grid grow place-items-start overflow-hidden">
            @include('titan_operator::frontend-ui.views.welcome')
            @include('titan_operator::frontend-ui.views.routes')
            @include('titan_operator::frontend-ui.components.loader')
        </div>

        @include('titan_operator::frontend-ui.components.floating-bar')

        @include('titan_operator::frontend-ui.components.footer')
    </div>

    @include('titan_operator::frontend-ui.components.trigger-button')
</div>

<link
    rel="stylesheet"
    href="{{ custom_theme_url('/assets/libs/prism/prism.css') }}"
/>
<link
    rel="stylesheet"
    href="{{ custom_theme_url('/assets/libs/picmo/picmo.min.css') }}"
/>
<script src="{{ custom_theme_url('/assets/libs/prism/prism.js') }}"></script>
<script src="{{ custom_theme_url('/assets/libs/beautify-html.min.js') }}"></script>
<script src="{{ custom_theme_url('/assets/libs/markdown-it.min.js') }}"></script>
<script src="{{ custom_theme_url('/assets/libs/turndown.js') }}"></script>
<script src="{{ custom_theme_url('/assets/libs/picmo/picmo.min.js') }}"></script>
<script
    defer
    src="{{ asset('vendor/titan-operator/js/alpine.min.js') }}"
></script>

@if (\App\Helpers\Classes\MarketplaceHelper::isRegistered('titan_operator_agent'))
    <script
        src="https://cdn.ably.com/lib/ably.min-1.js"
        type="text/javascript"
    ></script>
@endif

@include('titan_operator::frontend-ui.frontend-ui-scripts', ['is_editor' => false])
