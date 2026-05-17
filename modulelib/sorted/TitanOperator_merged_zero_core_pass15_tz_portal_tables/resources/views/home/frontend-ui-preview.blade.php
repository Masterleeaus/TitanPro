{{-- This is the frontend ui but with some changes for the editor --}}

@push('css')
    <style>
        .lqd-titan_operator-preview .lqd-ext-titan-operator-window {
            width: var(--lqd-ext-chat-window-w);
            height: var(--lqd-ext-chat-window-h);
        }
    </style>
@endpush

<div class="lqd-titan_operator-preview sticky bottom-8">
    @include('titan_operator::frontend-ui.frontend-ui', [
        'is_editor' => true,
        'is_iframe' => false,
    ])
</div>
