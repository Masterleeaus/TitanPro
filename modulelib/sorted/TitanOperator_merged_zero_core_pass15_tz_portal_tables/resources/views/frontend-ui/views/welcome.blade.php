@php
    $bg_image = custom_theme_url('/assets/img/external-titan_operator/chat-default-bg.jpg');
@endphp

<div
    class="lqd-ext-titan-operator-window-welcome-screen relative col-start-1 col-end-1 row-start-1 row-end-1 h-full w-full overflow-hidden bg-cover"
    style="background-image: url({{ $bg_image }}); background-position: top center;"
    x-show="currentView === 'welcome'"
    x-transition:enter="transition"
    x-transition:enter-start="opacity-0 translate-x-1"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-0"
>
    <div class="absolute start-0 top-0 h-full w-full bg-gradient-to-t from-[--lqd-ext-chat-foot-bg] from-25% to-transparent to-80%"></div>

    <div class="relative flex h-full flex-col gap-8 overflow-y-auto px-6 pb-28 pt-6">
        @include('titan_operator::frontend-ui.components.welcome-banner')

        @if ($is_editor || (isset($titan_operator) && ($titan_operator->is_links || $titan_operator->is_contact)))
            <div
                class="rounded-3xl border border-black/10 bg-white p-4 shadow-2xl"
                @if ($is_editor) x-show="activeTitanOperator.is_links || activeTitanOperator.is_contact" @endif
            >
                @include('titan_operator::frontend-ui.components.channels-list')
            </div>
        @endif

        <div class="rounded-3xl border border-black/10 bg-white p-4 shadow-2xl">
            @include('titan_operator::frontend-ui.components.conversations-list', ['recents' => true, 'take' => 3])
        </div>

        @if ($is_editor || (isset($titan_operator) && $titan_operator->is_articles))
            <div
                class="rounded-3xl border border-black/10 bg-white p-4 shadow-2xl"
                @if ($is_editor) x-show="activeTitanOperator.is_articles" @endif
            >
                @include('titan_operator::frontend-ui.components.articles-list', ['recents' => true, 'take' => 3])
            </div>
        @endif
    </div>
</div>
