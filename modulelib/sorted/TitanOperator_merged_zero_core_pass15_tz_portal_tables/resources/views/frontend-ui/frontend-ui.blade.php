{{-- This is the frontend ui --}}

@if ($is_editor)
    @include('titan_operator::frontend-ui.frontend-ui-editor')
@else
    @include('titan_operator::frontend-ui.frontend-ui-frontpage')
@endif
