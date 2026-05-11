@push('before-head-close')
    @vite('app/Extensions/TitanOperator/resources/assets/scss/external-titan_operator.scss')
    <link
        rel="stylesheet"
        href="{{ custom_theme_url('/assets/libs/picmo/picmo.min.css') }}"
    />
@endpush

<div
    class="lqd-ext-titan_operator"
    data-pos-x="right"
    data-pos-y="bottom"
    data-window-state="open"
    data-embedded="false"
    x-data="externalTitanOperator"
    :data-pos-x="activeTitanOperator.position"
    :style="{
        '--lqd-ext-chat-primary': activeTitanOperator.color,
        '--lqd-ext-chat-trigger-background': activeTitanOperator.trigger_background,
        '--lqd-ext-chat-window-w': `${testIframeWidth}px`,
        '--lqd-ext-chat-window-h': `${testIframeHeight}px`,
    }"
>
    <div
        class="lqd-ext-titan_operator-window before:pointer-events-none before:absolute before:bottom-0 before:z-3 before:h-40 before:w-full before:bg-gradient-to-t before:from-[--lqd-ext-chat-window-bg] before:from-40% before:to-transparent before:to-85%">
        <div class="lqd-ext-titan_operator-window-contents-wrap grid grow place-items-start overflow-hidden">
            @include('titan_operator::frontend-ui.views.welcome')
            @include('titan_operator::frontend-ui.views.routes')
        </div>

        @include('titan_operator::frontend-ui.components.floating-bar')

        @include('titan_operator::frontend-ui.components.footer')
    </div>

    @include('titan_operator::frontend-ui.components.trigger-bubble')

    @include('titan_operator::frontend-ui.components.trigger-button')
</div>

@push('script')
    <script src="{{ custom_theme_url('/assets/libs/picmo/picmo.min.js') }}"></script>

    @include('titan_operator::frontend-ui.frontend-ui-scripts', ['is_editor' => true])
@endpush
