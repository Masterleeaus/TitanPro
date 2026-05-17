{{--
  Action Button Shell Widget

  Renders a button that dispatches a generic CustomEvent when clicked.
  Real business actions are not executed in this pass.
--}}
<button
    class="titan-os-widget-action-button px-3 py-2 bg-blue-600 text-white rounded"
    @if(isset($widget['id'], $widget['action_key']))
        onclick="window.dispatchEvent(new CustomEvent('titan-os-widget-action', { detail: { widgetId: '{{ $widget['id'] }}', actionKey: '{{ $widget['action_key'] }}', payload: {{ json_encode($widget['payload'] ?? []) }} } }))"
    @endif
>
    {{ $widget['label'] ?? 'Action' }}
</button>